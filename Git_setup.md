# Debian12 rendszeren használva

    git version
Git version 2.39.5

    git config --global user.name "A Te Neved"
>A commitok mellett megjelenő név beállítása.<br>
--global Globális beállítások az összes repository-hoz amit a gépen létrehozunk.<br>

    git config --global user.email "a.te.email@pelda.com"
>A commitok mellett megjelenő email cím beállítása.<br>
--global Globális beállítások az összes repository-hoz amit a gépen létrehozunk.<br>

    mkdir KTCH_Project_TeneT
>Létrehozza a KTCH_Project_TeneT nevű könyvtárat, amelyben a projekted lesz.<br>

    cd KTCH_Project_TeneT
>Beléptet a KTCH_Project_TeneT könyvtárba.<br>

    git config --global init.defaultBranch main
>Beállítja minden ezután létrehozott branch nevét main-re.<br>

    git branch -m main
>Átnevezi az aktuálisan használt branch nevét main-re.<br>

    git init
>Inicializálja a Git repository-t az aktuális könyvtárban, létrehozza a .git rejtett file-t.<br>
Innentől kezdve követi a könyvtárban lévő file-ok változásait.<br>

    echo "# KTCH_Project_TeneT" > README.md
>Létrehoz egy README.md file-t a könyvtárban, aminek .md (Markdown Documentation) a kiterjesztése és a "# KTCH_Project_TeneT" szöveget tartalmazza.<br>

    git add .
>Minden file-t hozzáad a stageing-, cache-hez.<br>

    git add README.md
>A README.md file-t hozzáadja a stageing-, cache-hez.<br>

    git status
>Megmutatja a repository aktuális állapotát.<br>
Mely file-ok vannak a staging, cache területen (commit-ra várva).<br>
Mely file-ok változtak, de még nincsenek hozzáadva a staging, cache területhez.<br>
Bármilyen egyéb releváns információ a repository-ról (például, ha egy fájlt töröltél, de még nem commit-oltad a törlést).<br>

    git restore --staged .
>Az összes file visszavonása a staged-ről.<br>

    git restore --staged README.md
>A README.md file visszavonása a staged-ről.<br>

    git rm --cached README.md
>git rm: Eltávolít egy fájlt a Git nyilvántartásából (általában a file-t törli is a fájlrendszerből).<br>
--cached: Ez az opció azt mondja a Gitnek, hogy csak a staging területről távolítsa el a file-t, de hagyja meg a file-t a könyvtárban. A file innentől kezdve nem lesz követve a Git által, amíg újra hozzá nem adod.<br>

    git restore --staged README.md
>git restore: Eredetileg a file-okat egy korábbi commit állapotára állítja vissza.<br>
--staged: Ez az opció csak a staging területen végzi el a visszaállítást. A staging területről kivonja a file-t, de a file maga a munkakönyvtárban megmarad az utolsó commit szerinti állapotában.<br>

Különbségek:

    git rm --cached
>Eltávolítja a file-t a Git követéséből (azaz a következő commit után a Git többé nem fogja nyomon követni a file-t).<br>
A file megmarad a könyvtárban, de a Git számára úgy fog kinézni, mintha már nem létezne.<br>
Akkor hasznos, ha egy file-t már nem akarsz nyomon követni, de megtartanád a helyi file rendszeredben.<br>

    git restore --staged
>Csak a staging területről vonja vissza a file-t, és az utolsó commit állapotára állítja vissza. A file továbbra is követve lesz.<br>
Nem törli a file-t a Git nyomon követéséből, csak kivonja a staging területről.<br>
Ezek közül a git rm --cached akkor jön jól, ha végleg meg akarod szüntetni egy file követését, míg a git restore --staged csak a staging területen végzett műveletek visszavonására szolgál.<br>

    git commit -m "<üzenet>"
>Az -m opcióval közvetlenül beírható a commit-hoz kapcsolt üzenet.<br>
-m opció nélkül a Git megnyitja a szövegszerkesztőt.<br>

    git push -u origin main
Vagy teljes alakban:<br>

    git push -set--upstream origin main

>Beállítja az alapértelmezett upstream ágat, így nem kell minden további push-nál megadni azt.<br>
Az origin a remote prepository neve, amivel össze kell kötni a helyi gépen található repository-t.<br>
A main a branch/ág neve amit fel akarok tölteni.<br>

    git remote add origin https://github.com/<GitHub_Felhasználó_név>/<Pojekt_név>.git
>Összekapcsolja a helyi repository-t a GitHub reposytory-val.<br>

    git remote -v
>Megjeleníti a hozzáadott távoli repository-kat.<br>

    git push
>Feltölti a távoli repository-ba a local repository commit-olt változásokat.<br>

    git fetch
>Letölti a távoli repository-ból a változásokat anélkül, hogy hatással lenne a helyi munka könyvtárra.<br>
Akkor használjuk ezt a módszert ha le szeretnénk tölteni a távoli repository status-át, de meg akarjuk nézni a változásokat mielőtt frissítjük a local repository-t.<br>
Ahhoz, hogy érvénybe léptessük a változásokat a local branch-en, futtatnunk kell a "git merge" vagy "git rebase" utasításokat.<br>

    git pull
>Ez az utasítás kombinálja a "git fetch" és a "git merge" vagy a "git rebase" utasításokat egyetlen parancsba. Így letölti a változásokat a remote repository-ból és automatikusan integrálja azt az aktuális local branch-be.<br>
A "git pull" utasítás alkalmas a változások gyors letöltésére és integrálására a local branch-be, de ütközéseket okozhat, ezért különösen figyelni kell a használatára, főleg amikor több ember dolgozik ugyanazon a kódon.<br> 

    git merge
>https://www.atlassian.com/git/tutorials/merging-vs-rebasing<br>
https://www.simplilearn.com/git-rebase-vs-merge-article<br>
A "git merge" egyesíti a git branch-eket, miközben a commit-ok változatlanok maradnak.<br>

    git rebase
>https://medium.com/@turkelturk/git-rebase-explained-9f470329e942<br>
https://education.launchcode.org/linux/git/walkthrough/merging/git-rebase/index.html<br>
A "git rebase" szintén egyesíti a git branch-eket, de lehetőséget ad a commit-ok újraírására.<br>

Különbségek:<br>
A "git rebase" egybesűríti az összes változást és beilleszti egy új patch-be a cél ágon. Ezután végigvezeti a befejezett munkát egyik ágról a másikra, jellemzően a master ágon. A folyamat közben a "rebase" elsimítja a történetet, eltávolítva a nemkívánatos bejegyzéseket.<br>
<br>
Ezzel szemben a "git merge" csak a cél ágat módosítja és erről készít egy commit-ot, ezzel megőrizve az eredeti történetet. Ez sokszor átláthatatlanná teszi a sok commit-ot.<br>
Az alapvető különbség a "git merge" és a "git rebase" között, hogy a "merge" egyesíti a változásokat egy ágból (source branc) egy másik ágba (target branch), míg a "rebase" végigvezeti a változásokat az egyik ágtól a másik ágig.<br>
Akkor a jobb a "merge" használata ha a acél ág megosztásra kerül, míg a "rebase" ha a cél ág privát marad.<br>

    git reset
>Ez egy erős parancs amely visszavonja a változásokat, visszaállítja a repository-t az előző commit-ra, és mellőz minden változást ami ez után a commit után történt. A "reset" visszavonja a helyi változásokat.<br>

    git merge --squash
>"Take all these changes as if they happened in a single moment." [And there was light]<br>
https://graphite.dev/guides/git-merge-squash<br>
Ez a parancs lehetővé teszi, hogy az összes változást ami eddig a branch-en történt összepréselje egy egyszerű commit-ba azon az ágon amin éppen dolgozol. Ezzel az opcióval nem marad meg az összes eddigi commit, hanem egy commit készül, mintha minden változás most menne végbe.<br>
Az egyik fő oka ennek a metódusnak, hogy tisztán tartsuk a projekt history-t.<br>
Ne használd a "squash" parancsot ha meg akarod tartani a szerkezeteti változásokat a többszörös commit-tal! Ha a változások túlságosan kiterjedtek és szükség van az egyedi felülvizsgálatra.

    git checkout main
    git merge --squash <feature-branch-name>
    git commit -m "Squashed commit from feature-branch"


    git rebase -i
>Mivel a --squash MINDEN commit-ot egybesűrít, szükség lehet egy olyan variációra amellyel kiválasztható a megtartani kivánt commit-ok csoportja. Itt kerül képbe a "git rebase -i" (Interactive rebase).<br>

    git cherry-pick
>A művelet egy tetszőlegesen kiválasztott, múltbéli commit változtatásait alkalmazza a jelenlegi branchre.<br>
Mivel a gitben egy commit azonosítóját a változtatás tartalma és kezdőpontja együttesen határozza meg, a git "cherry-pick" egy teljesen új commitot fog létrehozni, hiába ugyanazt a változtatást alkalmazzuk.<br>

    git commit --fixup <commit azonosito>
>Korábbi commit javítása. Ezután ha végrehajtunk például egy rebase-t később, a Git összevonja a fixup commitot az eredetivel, eltüntetve így a "szemetelő" változtatást.
>
    git commit -m "Az A valtoztatas elkeszult!"
>
    git commit -m "A B valtoztatas elkeszult!"
>
    git commit -m "Fix A"
>

    fe4da2a Az A valtoztatas elkeszult!
    ac6dc87 A B valtoztatas elkeszult!
    743e6ff Fix A
>Letisztultabbá tehetjük a commitok történetét, ha az utolsó javítást a git commit --fixup ac6dc87 paranccsal végezzük el, így jelezve, hogy ez egy korábbi commit javítása. <br>

    git log --oneline

>Listázza az eddig végrehajtott commit-ok azonosítóját és megjegyzéseit:<br>
86bad9b (HEAD -> main, origin/main) Adatbátis létrehozás task.md<br>
60fd824 hairdress_air.txt és a task.md létrehozása<br>
e9ac7ae fixup! fetch, rebase, merge, reset stb<br>
964ed36 Update README.md<br>
5504436 fetch, rebase, merge, reset stb<br>
e85da7d (list) tili-toli<br>

<hr>
A git push parancsot követően a rendszer elkéri a GitHub felhasználó nevet, majd a GitHub által generált Personal Access Token-t.<br>
Felhasználónév: a GitHub felhasználóneved<br>
Jelszó: az előállított Personal Access Token<br>
<br>
A Personal Access Token, PAT generálása a GitHub oldalon történik.<br>
Lépj be a GitHub fiókodba, majd kattints a profilképedre a jobb felső sarokban, és válaszd a Settings menüpontot.<br>
A bal oldali menüben görgess le a Developer settings menüponthoz, és kattints rá.<br>
Válaszd ki a Personal access tokens lehetőséget, majd azon belül a Tokens (classic) fülön nyomj a Generate new token gombra.<br>
Adj meg egy nevet (például "Git token"), és állíts be egy lejárati időt. Az alapértelmezett 30 nap, de adhatsz hosszabb időt is, ha szeretnéd.<br>
Válaszd ki az engedélyeket. A legtöbb esetben a repo (repository management) jogosultság elegendő lesz.<br>
Kattints a Generate token gombra, és másold ki a létrehozott tokent. Jegyezd meg mert később nem tudod újra lekérni a tokent, szóval győződj meg róla, hogy elmented valahova biztonságosan.<br>
<hr>
Ha nem szeretnéd minden push-nál beírni a PAT jelszódat, használhatod a Credential Manager-t, ami tárolja és szükség esetén beilleszti azt.<br>
A PAT jelszót tárolhatod ideiglenesen, amíg újra nem indítod a számítógépet, vagy maradandóan a számítőgép újraindítását követően is.<br>
<br>
Linux/macOS

    git config --global credential.helper cache

Ideiglenesen, vagy:<br>

    git config --global credential.helper store

maradandóan.<br>

>cache: Ez az opció csak ideiglenesen tárolja a hitelesítő adatokat, és a gép újraindításakor elfelejti azokat.<br>
store: Ez tartósan elmenti a hitelesítő adatokat egy egyszerű szöveges file-ba, amely a .gitconfig könyvtárban található, így a gép újraindítása után is emlékezni fog rá.<br>

Windows

    git config --global credential.helper manager-core

macOS

    git config --global credential.helper osxkeychain
>Ez a parancs az Apple Keychain-t használja, így a PAT tárolva lesz a macOS rendszer saját kulcstartó alkalmazásában.<br>

