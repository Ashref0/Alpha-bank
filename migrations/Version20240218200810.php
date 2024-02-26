<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218200810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE credit (id INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, montant_emprunte DOUBLE PRECISION NOT NULL, date_demande DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', taux_interet DOUBLE PRECISION NOT NULL, nb_mois INT NOT NULL, date_emission DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', date_echeance DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', status TINYINT(1) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE remboursement (id INT AUTO_INCREMENT NOT NULL, credit_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, date_remboursement DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_C0C0D9EFCE062FF9 (credit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE remboursement ADD CONSTRAINT FK_C0C0D9EFCE062FF9 FOREIGN KEY (credit_id) REFERENCES credit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE remboursement DROP FOREIGN KEY FK_C0C0D9EFCE062FF9');
        $this->addSql('DROP TABLE credit');
        $this->addSql('DROP TABLE remboursement');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
