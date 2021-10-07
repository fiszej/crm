<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211007081049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee ADD name VARCHAR(255) NOT NULL, ADD phone VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE task ADD employee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB258C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_527EDB258C03F15C ON task (employee_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee DROP name, DROP phone');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB258C03F15C');
        $this->addSql('DROP INDEX IDX_527EDB258C03F15C ON task');
        $this->addSql('ALTER TABLE task DROP employee_id');
    }
}
