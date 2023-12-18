<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218003052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__pomodoro_session AS SELECT id, user_id, start_time, work_duration, break_duration, status, completed_pomodoros, end_time FROM pomodoro_session');
        $this->addSql('DROP TABLE pomodoro_session');
        $this->addSql('CREATE TABLE pomodoro_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, start_time DATETIME NOT NULL, work_duration INTEGER DEFAULT NULL, break_duration INTEGER NOT NULL, status VARCHAR(255) NOT NULL, completed_pomodoros INTEGER NOT NULL, end_time DATETIME NOT NULL, CONSTRAINT FK_6FFF4BB2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO pomodoro_session (id, user_id, start_time, work_duration, break_duration, status, completed_pomodoros, end_time) SELECT id, user_id, start_time, work_duration, break_duration, status, completed_pomodoros, end_time FROM __temp__pomodoro_session');
        $this->addSql('DROP TABLE __temp__pomodoro_session');
        $this->addSql('CREATE INDEX IDX_6FFF4BB2A76ED395 ON pomodoro_session (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__pomodoro_session AS SELECT id, user_id, start_time, work_duration, break_duration, status, completed_pomodoros, end_time FROM pomodoro_session');
        $this->addSql('DROP TABLE pomodoro_session');
        $this->addSql('CREATE TABLE pomodoro_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, start_time DATETIME NOT NULL, work_duration INTEGER NOT NULL, break_duration INTEGER NOT NULL, status VARCHAR(255) NOT NULL, completed_pomodoros INTEGER NOT NULL, end_time DATETIME NOT NULL, CONSTRAINT FK_6FFF4BB2A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO pomodoro_session (id, user_id, start_time, work_duration, break_duration, status, completed_pomodoros, end_time) SELECT id, user_id, start_time, work_duration, break_duration, status, completed_pomodoros, end_time FROM __temp__pomodoro_session');
        $this->addSql('DROP TABLE __temp__pomodoro_session');
        $this->addSql('CREATE INDEX IDX_6FFF4BB2A76ED395 ON pomodoro_session (user_id)');
    }
}
