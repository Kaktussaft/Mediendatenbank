<?php

namespace App\Model;

use DateTime;

class UserModel
{
    private int $id; 
    private string $name;
    private string $surname;
    private string $username;
    private DateTime $registrationDate;
    private string $isAdmin;
    private string $email; 
    
    public function __construct(string $name, string $surname, string $username, DateTime $registrationDate, string  $isAdmin,   string $email)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->username = $username;
        $this->registrationDate = $registrationDate;
        $this->isAdmin = $isAdmin;
        $this->email = $email;
    } 
    
    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getSurname(): string {
        return $this->surname;
    }

    public function setSurname(string $surname): void {
        $this->surname = $surname;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function getRegistrationDate(): DateTime {
        return $this->registrationDate;
    }

    public function setRegistrationDate(DateTime $registrationDate): void {
        $this->registrationDate = $registrationDate;
    }

    public function getIsAdmin(): string {
        return $this->isAdmin;
    }

    public function setIsAdmin(string $isAdmin): void {
        $this->isAdmin = $isAdmin;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

   
}