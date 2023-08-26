create table brand
(
    id         int auto_increment
        primary key,
    name       varchar(100) not null,
    created_at datetime     not null comment '(DC2Type:datetime_immutable)',
    updated_at datetime     null comment '(DC2Type:datetime_immutable)'
)
    collate = utf8mb4_unicode_ci;

create table client
(
    id         int auto_increment
        primary key,
    name       varchar(50) not null,
    created_at datetime    not null comment '(DC2Type:datetime_immutable)',
    updated_at datetime    null comment '(DC2Type:datetime_immutable)'
)
    collate = utf8mb4_unicode_ci;

create table doctrine_migration_versions
(
    version        varchar(191) not null
        primary key,
    executed_at    datetime     null,
    execution_time int          null
)
    collate = utf8mb3_unicode_ci;

create table mobile
(
    id          int auto_increment
        primary key,
    brand_id    int          not null,
    name        varchar(100) not null,
    description longtext     not null,
    quantity    int          not null,
    created_at  datetime     not null comment '(DC2Type:datetime_immutable)',
    updated_at  datetime     null comment '(DC2Type:datetime_immutable)',
    constraint FK_3C7323E044F5D008
        foreign key (brand_id) references brand (id)
)
    collate = utf8mb4_unicode_ci;

create index IDX_3C7323E044F5D008
    on mobile (brand_id);

create table refresh_tokens
(
    id            int auto_increment
        primary key,
    refresh_token varchar(128) not null,
    username      varchar(255) not null,
    valid         datetime     not null,
    constraint UNIQ_9BACE7E1C74F2195
        unique (refresh_token)
)
    collate = utf8mb4_unicode_ci;

create table user
(
    id         int auto_increment
        primary key,
    client_id  int          not null,
    email      varchar(180) not null,
    roles      json         not null,
    password   varchar(255) not null,
    created_at datetime     not null comment '(DC2Type:datetime_immutable)',
    updated_at datetime     null comment '(DC2Type:datetime_immutable)',
    constraint UNIQ_8D93D649E7927C74
        unique (email),
    constraint FK_8D93D64919EB6921
        foreign key (client_id) references client (id)
)
    collate = utf8mb4_unicode_ci;

create index IDX_8D93D64919EB6921
    on user (client_id);


