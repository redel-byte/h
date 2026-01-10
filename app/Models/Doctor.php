<?php

class Doctor extends User
{
    protected ?string $specialization = null;
    protected ?int $department_id = null;

    public function __construct(array $data = [])
    {
        $this->fill($data);
        $this->setRole('doctor');
    }

    public function getSpecialization(): ?string
    {
        return $this->specialization;
    }

    public function setSpecialization(string $specialization): void
    {
        $this->specialization = $specialization;
    }

    public function getDepartmentId(): ?int
    {
        return $this->department_id;
    }

    public function setDepartmentId(int $department_id): void
    {
        $this->department_id = $department_id;
    }

    public function canManageAppointments(): bool
    {
        return true;
    }

    public function canCreatePrescriptions(): bool
    {
        return true;
    }

    public function canViewOwnAppointments(): bool
    {
        return true;
    }

    public function canViewLimitedStatistics(): bool
    {
        return true;
    }

    public function canManagePatients(): bool
    {
        return false;
    }

    public function canManageMedications(): bool
    {
        return false;
    }

    public function canManageDepartments(): bool
    {
        return false;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'specialization' => $this->specialization,
            'department_id' => $this->department_id
        ]);
    }
}