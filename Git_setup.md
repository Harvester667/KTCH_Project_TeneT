# Debian12 rendszeren használva

    git version
Git version 2.39.5

    git config --global user.name "A Te Neved"
>A commitok mellett megjelenő név.<br>

    git config --global user.email "a.te.email@pelda.com"
>A commitok mellett feltüntetett email cím.<br>
--global Globális beállítások az összes repository-hoz amit a gépen létrehozunk<br>

    mkdir KTCH_Project_TeneT
>Létrehozza a KTCH_Project_TeneT nevű könyvtárat, amely a projekted lesz.<br>

    cd KTCH_Project_TeneT
>Beléptet a KTCH_Project_TeneT könyvtárba.<br>

    git config --global init.defaultBranch main
>Beállítja az ezután létrehozott branch nevét main-re.<br>

    git branch -m main
>Átnevezi az aktuálisan használt branch nevét main-re.<br>

    git init
>Inicializálja a Git repository-t az aktuális könyvtárban, létrehozza a .git rejtett file-t, innentől követi a könyvtárban lévő file-ok változásait.<br>

    echo "# KTCH_Project_TeneT" > README.md
>Létrehoz egy README.md file-t a könyvtárban, aminek .md (Markdown Documentation) a kiterjesztése és a "# KTCH_Project_TeneT" szöveget tartalmazza.<br>

    git add .
>Minden file-t hozzáad a stageing-, cache-hez.<br>

    git add README.md
>A README.md file-t hozzáadja a stageing-, cache-hez.<br>

    git status
>Megmutatja a repository aktuális állapotát.<br>
Mely fájlok vannak a staging, cache területen (commit-ra várva).<br>
Mely fájlok változtak, de még nincsenek hozzáadva a staging, cache területhez.<br>
Bármilyen egyéb releváns információ a repository-ról (például, ha egy fájlt töröltél, de még nem commit-oltad a törlést).<br>

    git restore --staged .
>Az összes fájl visszavonása a staged-ről.<br>

    git restore --staged README.md
>A README.md file visszavonása a staged-ről.<br>

    git rm --cached README.md
>git rm: Eltávolít egy fájlt a Git nyilvántartásából (általában a fájlt törli is a fájlrendszerből).
--cached: Ez az opció azt mondja a Gitnek, hogy csak a staging területről távolítsa el a fájlt, de hagyja meg a fájlt a könyvtárban. A fájl innentől kezdve nem lesz követve a Git által, amíg újra nem adod hozzá.<br>

    git restore --staged README.md
>git restore: Eredetileg a fájlokat egy korábbi commit állapotára állítja vissza.<br>
--staged: Ez az opció csak a staging területen végzi el a visszaállítást. A staging területről kivonja a fájlt, de a fájl maga a munkakönyvtárban megmarad az utolsó commit szerinti állapotában.<br>

Különbségek:

    git rm --cached
>Eltávolítja a fájlt a Git követéséből (azaz a következő commit után a Git többé nem fogja nyomon követni a fájlt).<br>
A fájl megmarad a könyvtárban, de a Git számára úgy fog kinézni, mintha már nem létezne.<br>
Akkor hasznos, ha egy fájlt már nem akarsz nyomon követni, de megtartanád a helyi fájlrendszeredben.<br>

    git restore --staged
>Csak a staging területről vonja vissza a fájlt, és az utolsó commit állapotára állítja vissza. A fájl továbbra is követve lesz.<br>
Nem törli a fájlt a Git nyomon követéséből, csak kivonja a staging területről.<br>
Ezek közül a git rm --cached akkor jön jól, ha végleg meg akarod szüntetni egy fájl követését, míg a git restore --staged csak a staging területen végzett műveletek visszavonására szolgál.<br>

    git commit -m "<üzenet>"
>Az -m opcióval közvetlenül beírható a commit-hoz kapcsolt üzenet.<br>
-m opció nélkül a Git megnyitja a szövegszerkesztőt.<br>

    git push -u origin main
    git push -set--upstream origin main
>Beállítja az alapértelmezett upstream ágat, így nem kell minden további push-nál megadni azt.<br>
Az origin a remote prepository neve, amivel össze kell kötni a helyi gépen található repositoryt.<br>
A main a branch/ág neve amit fel akarok tölteni.<br>

    git remote add origin https://github.com/<GitHub_Felhasználó_név>/<Pojekt_név>.git
>Összekapcsolja a helyi repository-t a GitHub reposytory-val.<br>

    git remote -v
>Megjeleníti a hozzáadott távoli repository-kat.<br>

    git push
>Feltölti a távoli repository-ba a változásokat.<br>

    git fetch
>Letölti a távoli repository-ból a változásokat.<br>
<hr>
A git push parancsot követően a rendszer elkéri a GitHub felhasználó nevet, majd a GitHub által generált Personal Access Token-t.<br>
Felhasználónév: a GitHub felhasználóneved<br>
Jelszó: az előállított Personal Access Token<br>
A Personal Access Token, PAT generálása a GitHub oldalon történik.<br>
Lépj be a GitHub fiókodba, majd kattints a profilképedre a jobb felső sarokban, és válaszd a Settings menüpontot.<br>
A bal oldali menüben görgess le a Developer settings menüponthoz, és kattints rá.<br>
Válaszd ki a Personal access tokens lehetőséget, majd azon belül a Tokens (classic) fülön nyomj a Generate new token gombra.<br>
Adj meg egy nevet (például "Git token"), és állíts be egy lejárati időt. Az alapértelmezett 30 nap, de adhatsz hosszabb időt is, ha szeretnéd.<br>
Válaszd ki az engedélyeket. A legtöbb esetben a repo (repository management) jogosultság elegendő lesz.<br>
Kattints a Generate token gombra, és másold ki a létrehozott tokent. Jegyezd meg, hogy később nem tudod újra lekérni a tokent, szóval győződj meg róla, hogy elmented valahova biztonságosan.<br>
<hr>
Ha nem szeretnéd minden push-nál beírni a PAT jelszódat, használhatod a Credential Manager-t, ami tárolja és szükség esetén beilleszti azt.<br>
A PAT jelszót tárolhatod ideiglenesen, amíg újra nem indítod a számítógépet.<br>


Linux/macOS

    git config --global credential.helper cache
    git config --global credential.helper store
>cache: Ez az opció csak ideiglenesen tárolja a hitelesítő adatokat, és a gép újraindításakor elfelejti azokat.<br>
store: Ez tartósan elmenti a hitelesítő adatokat egy egyszerű szöveges fájlba, amely a .gitconfig könyvtárban található, így a gép újraindítása után is emlékezni fog rá.<br>

Windows

    git config --global credential.helper manager-core

macOS

    git config --global credential.helper osxkeychain
>Ez a parancs az Apple Keychain-t használja, így a PAT tárolva lesz a macOS rendszer saját kulcstartó alkalmazásában.<br>

Windows ?

    git config --global credential.helper manager-core
