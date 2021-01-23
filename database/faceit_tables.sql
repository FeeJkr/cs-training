create table faceit_players
(
    id serial not null,
    faceit_id varchar(255) not null,
    nickname varchar(255) not null,
    avatar varchar(255) not null,
    faceit_url varchar(255) not null,
    created_at timestamp default now() not null,
    updated_at timestamp
);

create unique index faceit_players_faceit_id_uindex
    on faceit_players (faceit_id);

create unique index faceit_players_id_uindex
    on faceit_players (id);

alter table faceit_players
    add constraint faceit_players_pk
        primary key (id);

create table faceit_players_games
(
    id serial not null,
    faceit_players_id int not null
        constraint faceit_players_games_faceit_players_id_fk
            references faceit_players
            on delete cascade,
    skill_level int not null,
    faceit_elo int not null,
    game_player_id varchar(255) not null,
    game_profile_id varchar(255) not null,
    created_at timestamp default now() not null,
    updated_at timestamp
);

create unique index faceit_players_games_id_uindex
    on faceit_players_games (id);

alter table faceit_players_games
    add constraint faceit_players_games_pk
        primary key (id);

create table faceit_matches
(
    id serial not null,
    faceit_id varchar(255) not null,
    game_mode varchar(255) not null,
    competition_type varchar(255) not null,
    status varchar(255) not null,
    map varchar(255) not null,
    rounds int not null,
    started_at timestamp not null,
    finished_at timestamp not null,
    faceit_url varchar(255),
    created_at timestamp default now() not null,
    updated_at timestamp
);

create unique index faceit_matches_faceit_id_uindex
    on faceit_matches (faceit_id);

create unique index faceit_matches_id_uindex
    on faceit_matches (id);

alter table faceit_matches
    add constraint faceit_matches_pk
        primary key (id);

create table faceit_matches_teams
(
    id serial not null,
    faceit_matches_id int not null
        constraint faceit_matches_teams_faceit_matches_id_fk
            references faceit_matches
            on delete cascade,
    name varchar(255) not null,
    is_win boolean not null,
    first_half_rounds int not null,
    second_half_rounds int not null,
    overtime_rounds int not null,
    final_rounds int not null,
    headshots_percentage float not null,
    created_at timestamp default now() not null,
    updated_at timestamp
);

create unique index faceit_matches_teams_id_uindex
    on faceit_matches_teams (id);

alter table faceit_matches_teams
    add constraint faceit_matches_teams_pk
        primary key (id);

create table faceit_matches_teams_players
(
    id serial not null,
    faceit_matches_teams_id int not null
        constraint faceit_matches_teams_players_faceit_matches_teams_id_fk
            references faceit_matches_teams
            on delete cascade,
    faceit_player_id varchar(255) not null,
    nickname varchar(255) not null,
    kills int not null,
    deaths int not null,
    assists int not null,
    headshots int not null,
    headshots_percentage float not null,
    triple_kills int not null,
    quadro_kills int not null,
    penta_kills int not null,
    mvps int not null,
    kd_ratio float not null,
    kr_ratio float not null,
    created_at timestamp default now() not null,
    updated_at timestamp
);

create unique index faceit_matches_teams_players_id_uindex
    on faceit_matches_teams_players (id);

alter table faceit_matches_teams_players
    add constraint faceit_matches_teams_players_pk
        primary key (id);

