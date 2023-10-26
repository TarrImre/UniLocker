# UniLocker készülőben...
## Téma általános leírása
Az általam készítendő projekt egy PWA (Progressive Web App) típusú alkalmazás, amely egy API segítségével összekapcsolódik egy ESP8266/ESP-12E eszközzel, ez egy olyan modul, amely segítségével lehetővé válik a kommunikáció a webalkalmazás és az eszköz között az interneten keresztül. Az alkalmazás fő célja mini elektromos zárak kezelése és azok vezérlése.
A felhasználóknak lehetőségük van regisztrálni az alkalmazásban, az adatok egy adatbázisban kerülnek eltárolásra. A regisztráció során a felhasználóknak meg kell adniuk az alábbi adatokat: vezeték és keresztnév, email, jelszó, Neptun kód, illetve opcionálisan az UniPass kártya azonosítóját.
Az UniPass kártyát az alábbi módon lehet megadni: a felhasználó eszközén engedélyezni kell az NFC-t, majd szimplán csak a telefon hátoldalához tartani a kártyát, a felhasználó visszajelzést kap, hogy sikeres volt-e a beolvasás, ha igen akkor szintén eltárolásra kerül.
Amikor a felhasználók szeretnék használni a szekrényeket, az alkalmazásban láthatják az éppen elérhetőket, ott kitudják választani a számukra megfelelőt, és onnan kezelhetik. Az UniPass kártyájukkal is nyithatják a szekrényt, csak hozzá kell érinteni a leolvasóhoz, de az alkalmazásban is lehetőségük van a nyitásra. Az alkalmazás szerver oldali logikája ellenőrzi az adatbázisban a felhasználókat, és ha rendelkeznek az adott szekrény kinyitásához szükséges jogosultsággal, akkor megengedi a szekrény kinyitását.
Az alkalmazás megvalósítása során fontos a biztonság. Az adatok tárolására és az adatátvitelre biztonságos protokollokat kell használnunk. Emellett fontos az is, hogy csak azok a felhasználók férjenek hozzá az adatokhoz és szolgáltatásokhoz, akik valóban jogosultak rá.
Az általam készített alkalmazás nagy előnye, hogy webes alkalmazásként elérhető, így a felhasználók számára nincs szükség külön alkalmazás telepítésére. 
