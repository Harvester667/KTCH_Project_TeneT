# Debian12 rendszeren használva

    git version
Git version 2.39.5

    git config --global user.name "A Te Neved"
A commitok mellett megjelenő név

    git config --global user.email "a.te.email@pelda.com"
A commitok mellett feltüntetett email cím
--global #Globális beállítások az összes repository-hoz amit a gépen létrehozunk

    mkdir KTCH_Project_TeneT
Létrehozza a KTCH_Project_TeneT nevű könyvtárat, amely a projekted lesz

    cd KTCH_Project_TeneT
Beléptet a KTCH_Project_TeneT könyvtárba

    git config --global init.defaultBranch main
Beállítja az ezután létrehozott branch nevét main-re

    git branch -m main
Átnevezi az aktuálisan használt branch nevét main-re

    git init
Inicializálja a Git repository-t az aktuális könyvtárban, létrehozza a .git rejtett file-t, innentől követi a könyvtárban lévő file-ok változásait

    echo "# KTCH_Project_TeneT" > README.md
Létrehoz egy README.md file-t a könyvtárban, aminek .md (Markdown Documentation) a kiterjesztése és a "# KTCH_Project_TeneT" szöveget tartalmazza

    git add .
Minden file-t hozzáad a stageing-, cache-hez

    git add README.md
A README.md file-t hozzáadja a stageing-, cache-hez

    git status
Megmutatja a repository aktuális állapotát
Mely fájlok vannak a staging, cache területen (commit-ra várva).
Mely fájlok változtak, de még nincsenek hozzáadva a staging, cache területhez.
Bármilyen egyéb releváns információ a repository-ról (például, ha egy fájlt töröltél, de még nem commit-oltad a törlést).

    git restore --staged .
Az összes fájl visszavonása a staged-ről

    git restore --staged README.md
A README.md file visszavonása a staged-ről

    git rm --cached README.md
git rm: Eltávolít egy fájlt a Git nyilvántartásából (általában a fájlt törli is a fájlrendszerből).
--cached: Ez az opció azt mondja a Gitnek, hogy csak a staging területről távolítsa el a fájlt, de hagyja meg a fájlt a könyvtárban. A fájl innentől kezdve nem lesz követve a Git által, amíg újra nem adod hozzá.

    git restore --staged README.md
git restore: Eredetileg a fájlokat egy korábbi commit állapotára állítja vissza.
--staged: Ez az opció csak a staging területen végzi el a visszaállítást. A staging területről kivonja a fájlt, de a fájl maga a munkakönyvtárban megmarad az utolsó commit szerinti állapotában.

Különbségek:

    git rm --cached
Eltávolítja a fájlt a Git követéséből (azaz a következő commit után a Git többé nem fogja nyomon követni a fájlt).
A fájl megmarad a könyvtárban, de a Git számára úgy fog kinézni, mintha már nem létezne.
Akkor hasznos, ha egy fájlt már nem akarsz nyomon követni, de megtartanád a helyi fájlrendszeredben.

    git restore --staged
Csak a staging területről vonja vissza a fájlt, és az utolsó commit állapotára állítja vissza. A fájl továbbra is követve lesz.
Nem törli a fájlt a Git nyomon követéséből, csak kivonja a staging területről.

Ezek közül a git rm --cached akkor jön jól, ha végleg meg akarod szüntetni egy fájl követését, míg a git restore --staged csak a staging területen végzett műveletek visszavonására szolgál.

    git commit -m "<üzenet>"
Az -m opcióval közvetlenül beírható a commit-hoz kapcsolt üzenet
-m opció nélkül a Git megnyitja a szövegszerkesztőt

    git push -u origin main
    git push -set--upstream origin main
Beállítja az alapértelmezett upstream ágat, így nem kell minden további push-nál megadni azt
Az origin a remote prepository neve, amivel össze kell kötni a helyi gépen található repositoryt
A main a branch/ág neve amit fel akarok tölteni

    git remote add origin https://github.com/<GitHub_Felhasználó_név>/<Pojekt_név>.git
Összekapcsolja a helyi repository-t a GitHub reposytory-val

    git remote -v
Megjeleníti a hozzáadott távoli repository-kat

    git push
Feltölti a távoli repository-ba a változásokat

    git fetch
Letölti a távoli repository-ból a változásokat

A git push parancsot követően a rendszer elkéri a GitHub felhasználó nevet, majd a GitHub által generált Personal Access Token-t
Felhasználónév: a GitHub felhasználóneved
Jelszó: az előállított Personal Access Token
A Personal Access Token, PAT generálása a GitHub oldalon történik.
Lépj be a GitHub fiókodba, majd kattints a profilképedre a jobb felső sarokban, és válaszd a Settings menüpontot.
A bal oldali menüben görgess le a Developer settings menüponthoz, és kattints rá.
Válaszd ki a Personal access tokens lehetőséget, majd azon belül a Tokens (classic) fülön nyomj a Generate new token gombra.
Adj meg egy nevet (például "Git token"), és állíts be egy lejárati időt. Az alapértelmezett 30 nap, de adhatsz hosszabb időt is, ha szeretnéd.
Válaszd ki az engedélyeket. A legtöbb esetben a repo (repository management) jogosultság elegendő lesz.
Kattints a Generate token gombra, és másold ki a létrehozott tokent. Jegyezd meg, hogy később nem tudod újra lekérni a tokent, szóval győződj meg róla, hogy elmented valahova biztonságosan.
Ha nem szeretnéd minden push-nál beírni a PAT jelszódat, használhatod a Credential Manager-t, ami tárolja és szükség esetén beilleszti azt.
A PAT jelszót tárolhatod ideiglenesen, amíg újra nem indítod a számítógépet

Linux/macOS
    git config --global credential.helper cache
    git config --global credential.helper store
cache: Ez az opció csak ideiglenesen tárolja a hitelesítő adatokat, és a gép újraindításakor elfelejti azokat.
store: Ez tartósan elmenti a hitelesítő adatokat egy egyszerű szöveges fájlba, amely a .gitconfig könyvtárban található, így a gép újraindítása után is emlékezni fog rá.

Windows
    git config --global credential.helper manager-core

macOS
    git config --global credential.helper osxkeychain
Ez a parancs az Apple Keychain-t használja, így a PAT tárolva lesz a macOS rendszer saját kulcstartó alkalmazásában.

Windows ?
    git config --global credential.helper manager-core
