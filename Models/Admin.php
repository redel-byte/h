<?php

class Admin extends User
{
    public function __construct(array $data = [])
    {
        $this->fill($data);
        $this->setRole('admin');
    }

    public function canManageDoctors(): bool
    {
        return true;
    }

    public function canManagePatients(): bool
    {
        return true;
    }

    public function canManageAppointments(): bool
    {
        return true;
    }

    public function canManageMedications(): bool
    {
        return true;
    }

    public function canViewStatistics(): bool
    {
        return true;
    }

    public function canManageDepartments(): bool
    {
        return true;
    }
}