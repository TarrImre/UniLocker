# UniLocker
Célkitűzésem volt, hogy egy olyan platformot hozzak létre, amely lehetővé teszi a hallgatók számára, hogy könnyedén és biztonságosan használják az egyetemi szekrényeket. Az alkalmazás lehetővé teszi a felhasználók számára a regisztrációt és az azonosítást, ideértve az UniPass kártyák használatát is, amelyek segítségével egy egyszerű érintéssel nyithatják ki a szekrényeiket. 

![Mockup](https://unideb.toxy.hu/icons/mockup.png)

## Leírás

Az UniLocker projekt egy olyan progresszív webalkalmazás, amelynek célja, hogy az oktatási intézményekben elérhető szekrények kezelését és vezérlését egyszerűsítse és biztonságossá tegye. A projekt egyesíti a modern webfejlesztési technológiák és az IoT (Internet of Things) eszközök világát.

### Az alkalmazás két fő része:
- Az első rész a webes felület, mely lehetővé teszi a felhasználók számára a szekrények kiválasztását és kezelését, valamint az azonosítást és regisztrációt.

- A második rész pedig az eszközökkel történő kommunikációt szolgálja, amelyek az egyetemi szekrényeket irányítják. Az alkalmazás lényeges eleme a biztonság, amely magába foglalja az adatok, tárgyak - tulajdonok védelmét és az azonosítás megbízhatóságát. Csak azok a felhasználók férnek hozzá az adatokhoz és jogosultságokhoz, akik erre valóban jogosultak.

![Connection](https://unideb.toxy.hu/icons/connection.png)

# Szoftver

![Screenshots](https://unideb.toxy.hu/icons/pwamerged.png)

## Admin felület

Az Adminisztrátor rang azt jelzi, hogy az adott felhasználó az oldal egyik legmagasabb szintű jogosultságával rendelkezik, és így teljeskörű hozzáférése van az oldal adminisztrációs funkcióihoz és beállításaihoz. 

![UniPass](https://unideb.toxy.hu/icons/adminoldal.png)

## NFC 
Az UniPass kártya olvasását a készülékekben található NFC (Near Field Communication) technológiával valósítottam meg. Az NFC egy vezeték nélküli kommunikációs technológia, amely lehetővé teszi az adatcserét rövid távolságon belül. Ennek segítségével az UniPass kártyák olvasása és az azokon található információk beolvasása gyors és hatékony módon történhet. 

![UniPass](https://unideb.toxy.hu/icons/unipass.png)

## Adatbázis
Az adatbázis három fő táblát tartalmaz: szekrények (lockers), felhasználók (users) és beállítások (settings).

![Database](https://unideb.toxy.hu/icons/adatb.png)

Szekrények (lockers) tábla:
- id: Ez egy egyedi azonosító, amely minden szekrényhez hozzá van rendelve. Automatikusan növekszik, és egyedi azonosítót biztosít a szekrények számára.
- status: Ez a mező lehetőséget ad arra, hogy nyomon kövessük a szekrények állapotát. Két értéke lehet: "off" vagy "on". Ezek a státuszok azt jelzik, hogy a szekrény éppen elérhető vagy foglalt.
- NeptunCode: Ez a mező tartalmazza a hallgató Neptun kódját, aki éppen használja a szekrényt. Ez lehetővé teszi a szekrényhasználat nyomon követését és a felhasználók azonosítását.
- UniPassCode: Ez a mező tárolja a hallgató Unipass kártya kódját, ami szintén segít azonosítani a felhasználót a kártyával történő szekrény nyitás közben.

Felhasználók (users) tábla:
- id: Ez a mező hasonlóan az előzőhöz egy egyedi azonosító, amely minden felhasználóhoz tartozik.
- VName és KName: Ezek a mezők tárolják a felhasználók vezeték és keresztnevét.
- Email: Az email cím segíti az azonosítást és a kapcsolattartást a felhasználókkal.
- Password: A jelszó biztonságosan eltárolva lehetővé teszi a felhasználók bejelentkezését.
- NeptunCode: Ez a mező tárolja a felhasználó Neptun kódját, ami egy másik azonosítási mód, illetve a belépéshez szükséges.
- UniPassCode: a regisztrációkor beolvasott Unipass kártya azonosítóját tároljuk el.
- CreatedAT: Ez a mező rögzíti azt az időpontot, amikor a felhasználó létrehozta a fiókját.
- Rank: A felhasználók rangja lehet "Student" vagy "Admin", és ezzel a jogosultságokat szabályozhatod. Az "Admin" rangú felhasználók például: további funkciókhoz vagy jogosultságokhoz férnek hozzá.

Beállítások (settings) tábla:
- id: szintén egy egyedi azonosító.
- settingsName: Ez a mező tárolja a beállítás nevét, amelyet később az alkalmazásban használhatsz.
- value: A beállítás értéke ebben a mezőben van tárolva, és lehetővé teszi a konfigurációs beállítások könnyű módosítását és kezelését.


