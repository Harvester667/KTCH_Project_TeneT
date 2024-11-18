>Programozási nyelvek kiválasztása:<br>
Felhasznált programok. Git, GitHub, Visual Code, Visual2022, Gambas3, PHPMyAdmin, Dia, Krita<br>
PHP-a felhasználói felülethez, felhasználói regisztrációhoz.<br>
SQL-az adatbázisban tárolt és feldolgozott információk kezeléséhez.<br>

>Programozási terv, térkép készítése<br>
Diagram terv készítése<rb>

>Felhasználói felület létrehozása:<br>
-kezdőképernyő<br>
-gombok és placeholderek létrehozása<br>
-stílus és effektek kiválasztása<br>

>Api létrehozása a put, post, get és delete kiszolgálásához</br>

>SQL adatbázis létrehozása:<br>
-mérföldkövek kijelölése, az első egy hónap<br>
-adatbázis létrehozása<br>
-táblák létrehozása<br>
-relációs kapcsolatok kialakítása<br>


    create database hairdress
    character set utf8
    collate utf8_hungarian_ci;

    use hairdress;

    create table customers (
        customer_id integer not null primary key auto_increment,
        first_name varchar(51) char() not null
        last_name varchar(51) char() not null,
        date_of_birth date not null,
        phone integer,
        email varchar(100) char() not null,
        sex null,
        nickname varchar(51) null,
        active boolean not null DEFAULT 'true'
    );



    delimiter //
        create procedure lekerdezes()
        begin
            select * from customers;
        end //
    delimiter;

    call lekerdezes();

    create user felhasznalo@localhost identified by '';

    insert into customers
    (first_name, last_name, date_of_birth, phone, email, sex, nickname)
    values
    ("", "", "", "", "", "", ""),
    ("", "", "", "", "", "", ""),
    ("", "", "", "", "", "", ""),
    ("", "", "", "", "", "", "");

    
>Tesztelés<br>
Műszaki leírás készítése<br>
Felhasználói leírás készítése<br>