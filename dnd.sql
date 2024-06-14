--
-- Starting Tables
--

drop table item cascade constraints;
drop table equippedinventory cascade constraints;
drop table carriedinventory cascade constraints;
drop table feat cascade constraints;
drop table feature cascade constraints;
drop table mainclass cascade constraints;
drop table subclass cascade constraints;
drop table race cascade constraints;
drop table background cascade constraints;
drop table character cascade constraints;
drop table characterclasses cascade constraints;
drop table weaponproperty cascade constraints;
drop table weaponattack cascade constraints;
drop table backgrounditem cascade constraints;

create table item
(
    item_id     number(9, 0) primary key,
    item_name   varchar2(64)                                       not null,
    item_type   varchar2(6) check (item_type in ('WEAPON', 'ETC')) not null,
    item_weight number(38, 2),
    item_cost   number
);

create table feat
(
    feat_name        varchar2(64) primary key,
    feat_description clob
);

create table feature
(
    feature_name        varchar2(64) primary key,
    feature_description clob,
    level_required      number(3, 0) not null,
    feat                varchar2(64) not null,
    foreign key (feat) references feat (feat_name)
);

create table mainclass
(
    class_name        varchar2(64) primary key,
    hit_die           varchar2(64) not null,
    class_description clob,
    feature           varchar2(64) not null,
    foreign key (feature) references feature (feature_name)
);

create table subclass
(
    class_name    varchar2(64),
    subclass_name varchar2(64),
    feature       varchar2(64) not null,
    primary key (class_name, subclass_name),
    foreign key (class_name) references mainclass (class_name),
    foreign key (feature) references feature (feature_name)
);

create table race
(
    race_name        varchar2(64) primary key,
    speed            number(38, 0) not null,
    race_description clob,
    feature          varchar2(64)  not null,
    foreign key (feature) references feature (feature_name)
);

create table background
(
    bgnd_name              varchar2(64) primary key,
    background_description clob,
    feature                varchar2(64) not null,
    foreign key (feature) references feature (feature_name)
);

create table character
(
    char_name varchar2(64) primary key,
    str       number(3, 0) not null,
    dex       number(3, 0) not null,
    con       number(3, 0) not null,
    int_stat  number(3, 0) not null,
    wis       number(3, 0) not null,
    chr       number(3, 0) not null,
    race_name varchar2(64) not null,
    bgnd_name varchar2(64) not null,
    foreign key (race_name) references race (race_name),
    foreign key (bgnd_name) references background (bgnd_name)
);

create table equippedinventory
(
    char_name varchar2(64),
    item_id   number(9, 0),
    primary key (char_name, item_id),
    foreign key (char_name) references character (char_name),
    foreign key (item_id) references item (item_id)
);

create table carriedinventory
(
    char_name varchar2(64),
    item_id   number(9, 0),
    primary key (char_name, item_id),
    foreign key (char_name) references character (char_name),
    foreign key (item_id) references item (item_id)
);

create table characterclasses
(
    char_name     varchar2(64),
    class_level   number(3, 0),
    class_name    varchar2(64),
    subclass_name varchar2(64),
    primary key (char_name),
    foreign key (char_name) references character (char_name),
    foreign key (class_name, subclass_name) references subclass (class_name, subclass_name)
);

create table weaponproperty
(
    item_id         number(9, 0),
    weapon_property varchar2(64) not null,
    primary key (item_id, weapon_property),
    foreign key (item_id) references item (item_id)
);

create table weaponattack
(
    item_id            number(9, 0),
    attack_name        varchar2(64),
    save               number(1, 0)                                                                         not null,
    check_ability      varchar2(3) check (check_ability in ('STR', 'DEX', 'CON', 'INT', 'WIS', 'CHR'))      not null,
    dmg_type           varchar2(11) check (dmg_type in
                                           ('PIERCING', 'BLUDGEONING', 'SLASHING', 'FIRE', 'COLD', 'LIGHTNING',
                                            'THUNDER', 'ACID', 'POISON', 'PSYCHIC', 'RADIANT', 'NECROTIC')) not null,
    dmg_bonus          number(9, 0),
    dmg_dice           varchar2(64)                                                                         not null,
    attack_bonus_or_dc number(9, 0)                                                                         not null,
    primary key (item_id, attack_name),
    foreign key (item_id) references item (item_id)
);

create table backgrounditem
(
    bgnd_name varchar2(64),
    item_id   number(9, 0),
    primary key (bgnd_name, item_id),
    foreign key (bgnd_name) references background (bgnd_name),
    foreign key (item_id) references item (item_id)
);

insert into item
values (1, 'Sword', 'WEAPON', 5.0, 25.0);
insert into item
values (2, 'Potion of Healing', 'ETC', 0.5, 50.0);
insert into item
values (3, 'Bow', 'WEAPON', 3.0, 40.0);
insert into item
values (4, 'Scroll of Fireball', 'ETC', 0.1, 100.0);
insert into item
values (5, 'Axe', 'WEAPON', 4.0, 30.0);

insert into feat
values ('Feat1', 'Description for Feat1');
insert into feat
values ('Feat2', 'Description for Feat2');
insert into feat
values ('Feat3', 'Description for Feat3');
insert into feat
values ('Feat4', 'Description for Feat4');
insert into feat
values ('Feat5', 'Description for Feat5');

insert into feature
values ('Feature1', 'Description for Feature 1', 1, 'Feat1');
insert into feature
values ('Feature2', 'Description for Feature 2', 2, 'Feat2');
insert into feature
values ('Feature3', 'Description for Feature 3', 3, 'Feat3');
insert into feature
values ('Feature4', 'Description for Feature 4', 4, 'Feat4');
insert into feature
values ('Feature5', 'Description for Feature 5', 5, 'Feat5');

insert into mainclass
values ('Warrior', 'd3', 'Description for Warrior class', 'Feature1');
insert into mainclass
values ('Ranger', 'd4', 'Description for Ranger class', 'Feature2');
insert into mainclass
values ('Cleric', 'd5', 'Description for Cleric class', 'Feature3');
insert into mainclass
values ('Barbarian', 'd1', 'Description for Barbarian class', 'Feature4');
insert into mainclass
values ('Wizard', 'd2', 'Description for Wizard class', 'Feature5');

insert into subclass
values ('Warrior', 'Battle Master', 'Feature1');
insert into subclass
values ('Ranger', 'Gloom Stalker Conclave', 'Feature2');
insert into subclass
values ('Cleric', 'Life Domain', 'Feature3');
insert into subclass
values ('Barbarian', 'Path of the Beast', 'Feature4');
insert into subclass
values ('Wizard', 'School of Abjuration', 'Feature5');

insert into race
values ('Human', 12, 'Description for Human', 'Feature1');
insert into race
values ('Elf', 10, 'Description for Elf', 'Feature2');
insert into race
values ('Dwarf', 8, 'Description for Dwarf', 'Feature3');
insert into race
values ('Half-Orc', 15, 'Description for Half-Orc', 'Feature4');
insert into race
values ('Gnome', 17, 'Description for Gnome', 'Feature5');

insert into background
values ('Noble', 'Description for Noble background', 'Feature1');
insert into background
values ('Criminal', 'Description for Criminal background', 'Feature2');
insert into background
values ('Sage', 'Description for Sage background', 'Feature3');
insert into background
values ('Folk Hero', 'Description for Folk Hero background', 'Feature4');
insert into background
values ('Acolyte', 'Description for Acolyte background', 'Feature5');

insert into character
values ('Character1', 12, 14, 10, 8, 15, 16, 'Human', 'Noble');
insert into character
values ('Character2', 14, 10, 12, 16, 8, 10, 'Elf', 'Criminal');
insert into character
values ('Character3', 8, 16, 14, 10, 12, 14, 'Dwarf', 'Sage');
insert into character
values ('Character4', 10, 12, 14, 14, 16, 10, 'Half-Orc', 'Folk Hero');
insert into character
values ('Character5', 16, 8, 12, 12, 14, 10, 'Gnome', 'Acolyte');

insert into equippedinventory
values ('Character1', 1);
insert into equippedinventory
values ('Character2', 2);
insert into equippedinventory
values ('Character3', 3);
insert into equippedinventory
values ('Character4', 4);
insert into equippedinventory
values ('Character5', 5);

insert into carriedinventory
values ('Character1', 1);
insert into carriedinventory
values ('Character2', 2);
insert into carriedinventory
values ('Character3', 3);
insert into carriedinventory
values ('Character4', 4);
insert into carriedinventory
values ('Character5', 5);

insert into characterclasses
values ('Character1', 5, 'Warrior', 'Battle Master');
insert into characterclasses
values ('Character2', 5, 'Ranger', 'Gloom Stalker Conclave');
insert into characterclasses
values ('Character3', 5, 'Cleric', 'Life Domain');
insert into characterclasses
values ('Character4', 5, 'Barbarian', 'Path of the Beast');
insert into characterclasses
values ('Character5', 5, 'Wizard', 'School of Abjuration');

insert into weaponproperty
values (1, 'Two-Handed');
insert into weaponproperty
values (2, 'Versatile');
insert into weaponproperty
values (3, 'Ranged');
insert into weaponproperty
values (4, 'Magical');
insert into weaponproperty
values (5, 'Finesse');

insert into weaponattack
values (1, 'Slash', 0, 'STR', 'SLASHING', 2, '1d8', 4);
insert into weaponattack
values (2, 'Shoot', 0, 'DEX', 'PIERCING', 2, '1d10', 5);
insert into weaponattack
values (3, 'Stab', 0, 'DEX', 'PIERCING', 3, '1d6', 3);
insert into weaponattack
values (4, 'Heal', 0, 'WIS', 'RADIANT', 0, '2d4', 0);
insert into weaponattack
values (5, 'Fireball', 1, 'INT', 'FIRE', 8, '3d6', 15);

insert into backgrounditem
values ('Noble', 1);
insert into backgrounditem
values ('Criminal', 2);
insert into backgrounditem
values ('Sage', 3);
insert into backgrounditem
values ('Folk Hero', 4);
insert into backgrounditem
values ('Acolyte', 5);