<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231221174412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX unique_smth ON tweet (author_id, text)');
        $this->addSql('ALTER TABLE "user" ADD password VARCHAR(32) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD age INT NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD is_active BOOLEAN NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX unique_smth');
        $this->addSql('ALTER TABLE "user" DROP password');
        $this->addSql('ALTER TABLE "user" DROP age');
        $this->addSql('ALTER TABLE "user" DROP is_active');
    }
}
