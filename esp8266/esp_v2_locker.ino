#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>
#include <MFRC522.h>
#include "Wifi.h"

const char* ssid = wifi_ssid;
const char* password = wifi_password;
const char* apikeyUrl = "http://api.toxy.hu/read_api.php?esp";

const int SS_PIN = D8;
const int RST_PIN = D0;
const int relayPins[] = { D1, D2, D3 };
const int numLockers = 3;
const unsigned long unlockDuration = 500;
const int ledPin = D4;

const unsigned long apiRefreshInterval = 400;  // Példa: 0.4 másodperc

unsigned long lastApiRefreshTime = 0;  // Utolsó API frissítés ideje


WiFiClient client;
MFRC522 rfid(SS_PIN, RST_PIN);
MFRC522::MIFARE_Key key;
String tag = "";
String apiKey = "";
String apiUrl;

enum LockerState {
  LOCKED,
  UNLOCKED
};

struct Locker {
  LockerState state;
  unsigned long unlockTime;
};

DynamicJsonDocument doc(4352);
Locker lockers[numLockers];

void setup() {
  Serial.begin(115200);
  delay(100);
  SPI.begin();
  rfid.PCD_Init();
  pinMode(ledPin, OUTPUT);
  digitalWrite(ledPin, LOW);
  connectToWiFi();
  if (getApiKey(apikeyUrl)) {
    digitalWrite(ledPin, LOW);
    Serial.println("API Key loaded successfully: " + apiKey);
  } else {
    for (int i = 0; i < 5; i++) {
      Serial.println("Failed to load API Key");
      digitalWrite(ledPin, HIGH);
      delay(500);
      digitalWrite(ledPin, LOW);
      delay(500);
    }
    ESP.restart();
  }
  for (int i = 0; i < numLockers; i++) {
    pinMode(relayPins[i], OUTPUT);
    digitalWrite(relayPins[i], HIGH);
  }
}

void connectToWiFi() {
  WiFi.begin(ssid, password);
  int retries = 0;
  while (WiFi.status() != WL_CONNECTED && retries < 15) {
    digitalWrite(ledPin, HIGH);
    delay(500);
    digitalWrite(ledPin, LOW);
    delay(500);
    Serial.println("Csatlakozás a hálózatra...");
    retries++;
  }
  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("Sikeres kapcsolat!");
  } else {
    Serial.println("Nem sikerült csatlakozni a hálózathoz!");
    digitalWrite(ledPin, HIGH);
    delay(30000);
    connectToWiFi();
  }
}

bool getApiKey(const char* url) {
  HTTPClient http;
  http.begin(client, url);
  int httpCode = http.GET();
  if (httpCode > 0) {
    String payload = http.getString();
    DeserializationError error = deserializeJson(doc, payload);
    if (!error) {
      apiKey = doc["encrypt_api_key"].as<String>();
      apiUrl = "http://api.toxy.hu/read_all.php?id=1&apikey=" + apiKey;
      return true;
    }
  }
  return false;
}

void unlockLocker(int lockerIndex) {
  if (lockers[lockerIndex].state == LOCKED) {
    digitalWrite(relayPins[lockerIndex], LOW);
    lockers[lockerIndex].state = UNLOCKED;
    lockers[lockerIndex].unlockTime = millis();
  }
}

void lockLocker(int lockerIndex) {
  if (lockers[lockerIndex].state == UNLOCKED) {
    digitalWrite(relayPins[lockerIndex], HIGH);
    lockers[lockerIndex].state = LOCKED;
  }
}

void handleLocker(int lockerIndex, const char* status, const String& uniPassCode) {
  if (strcmp(status, "off") == 0) {
    lockLocker(lockerIndex);
  } else if (strcmp(status, "on") == 0) {
    unlockLocker(lockerIndex);
  }
}

void readRFID() {
  if (rfid.PICC_IsNewCardPresent() && rfid.PICC_ReadCardSerial()) {
    // Read the UID of the RFID card
    String rfidTag = "";
    for (byte i = 0; i < rfid.uid.size; i++) {
      rfidTag += String(rfid.uid.uidByte[i] < 0x10 ? "0" : "");
      rfidTag += String(rfid.uid.uidByte[i], HEX);
    }
    
    // Check if the RFID tag matches the UniPass
    for (int i = 0; i < numLockers; i++) {
      String unipass = doc["led"][String(i + 1)][0]["UniPassCode"].as<String>();
      if (rfidTag == unipass) {
        unlockLocker(i);
 
        Serial.println("Locker " + String(i + 1) + " opened for tag: " + rfidTag);
      }
    }
    
    rfid.PICC_HaltA();
    rfid.PCD_StopCrypto1();
  }
}

void loop() {

  readRFID();
  for (int i = 0; i < numLockers; i++) {
    if (lockers[i].state == UNLOCKED && (millis() - lockers[i].unlockTime) >= unlockDuration) {
      lockLocker(i);
    }

    unsigned long currentTime = millis();
    if (currentTime - lastApiRefreshTime >= apiRefreshInterval) {
      // update the last update time
      lastApiRefreshTime = currentTime;
      if (lockers[i].state == LOCKED) {
        HTTPClient http;
        http.begin(client, apiUrl);
        int httpCode = http.GET();
        if (httpCode > 0) {
          String payload = http.getString();
          DeserializationError error = deserializeJson(doc, payload);
          if (!error) {
            for (int i = 0; i < numLockers; i++) {
              JsonObject lockerData = doc["led"][String(i + 1)][0];
              const char* status = lockerData["status"];
              String unipass = lockerData["UniPassCode"].as<String>();

              Serial.println();
              Serial.print("Locker ");
              Serial.println(i + 1);
              Serial.print("status: ");
              Serial.println(status);
              Serial.print("UniPassCode: ");
              Serial.println(unipass);

              digitalWrite(relayPins[i], (tag == unipass && !unipass.isEmpty()) ? LOW : HIGH);
              handleLocker(i, status, unipass);
            }
          }
        } else {
          Serial.print("Failed to connect to locker ");
          Serial.println(i + 1);
          digitalWrite(ledPin, HIGH);
          delay(500);
          digitalWrite(ledPin, LOW);
          delay(500);
        }
        http.end();
      }
    }
  }
}
