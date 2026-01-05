<?php

class Patient extends User
{
    protected ?string $gender = null;
    protected ?DateTime $date_of_birth = null;
    protected ?string $address = null;

    public function __construct(array $data = [])
    {
        $this->fill($data);
        $this->setRole('patient');
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getDateOfBirth(): ?DateTime
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(DateTime $date_of_birth): void
    {
        $this->date_of_birth = $date_of_birth;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getAge(): ?int
    {
        if ($this->date_of_birth === null) {
            return null;
        }

        $today = new DateTime();
        $age = $today->diff($this->date_of_birth)->y;
        
        return $age;
    }

    public function canBookAppointments(): bool
    {
        return true;
    }

    public function canViewOwnAppointments(): bool
    {
        return true;
    }

    public function canViewOwnPrescriptions(): bool
    {
        return true;
    }

    public function canCreatePrescriptions(): bool
    {
        return false;
    }

    public function canManageAppointments(): bool
    {
        return false;
    }

    public function canViewStatistics(): bool
    {
        return false;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
            'address' => $this->address,
            'age' => $this->getAge()
        ]);
    }
}