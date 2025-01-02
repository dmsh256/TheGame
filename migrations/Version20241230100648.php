<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241230100648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initializing database.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            <<<SQL
                create sequence result_pk_increment
                    as integer
                    minvalue 0;         
SQL
        );

        $this->addSql(
            <<<SQL
                create table public.result
                    (
                        id        integer   default nextval('result_pk_increment'::regclass) not null
                            constraint results_pk
                                primary key,
                        name      varchar(8)                                                 not null,
                        score     integer                                                    not null,
                        timestamp timestamp default CURRENT_TIMESTAMP                        not null
                    );
SQL
        );

        $this->addSql('alter table result owner to symfony');
        $this->addSql('alter sequence result_pk_increment owner to symfony');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop table result');
        $this->addSql('drop sequence result_pk_increment');
    }
}
