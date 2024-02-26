<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218224523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE credit CHANGE date_demande date_demande DATE NOT NULL, CHANGE date_emission date_emission DATE NOT NULL, CHANGE date_echeance date_echeance DATE NOT NULL');
        $this->addSql('ALTER TABLE remboursement CHANGE date_remboursement date_remboursement DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE credit CHANGE date_demande date_demande DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', CHANGE date_emission date_emission DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', CHANGE date_echeance date_echeance DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE remboursement CHANGE date_remboursement date_remboursement DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }
}
