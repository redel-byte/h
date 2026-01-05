<?php

class Prescription extends BaseModel
{
    protected ?int $id = null;
    protected ?int $doctor_id = null;
    protected ?int $patient_id = null;
    protected ?int $medication_id = null;
    protected ?string $dosage = null;
    protected ?string $instructions = null;
    protected ?DateTime $start_date = null;
    protected ?DateTime $end_date = null;
    protected ?DateTime $created_at = null;
    protected ?DateTime $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDoctorId(): ?int
    {
        return $this->doctor_id;
    }

    public function setDoctorId(int $doctor_id): void
    {
        $this->doctor_id = $doctor_id;
    }

    public function getPatientId(): ?int
    {
        return $this->patient_id;
    }

    public function setPatientId(int $patient_id): void
    {
        $this->patient_id = $patient_id;
    }

    public function getMedicationId(): ?int
    {
        return $this->medication_id;
    }

    public function setMedicationId(int $medication_id): void
    {
        $this->medication_id = $medication_id;
    }

    public function getDosage(): ?string
    {
        return $this->dosage;
    }

    public function setDosage(string $dosage): void
    {
        $this->dosage = $dosage;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function setInstructions(string $instructions): void
    {
        $this->instructions = $instructions;
    }

    public function getStartDate(): ?DateTime
    {
        return $this->start_date;
    }

    public function setStartDate(DateTime $start_date): void
    {
        $this->start_date = $start_date;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->end_date;
    }

    public function setEndDate(DateTime $end_date): void
    {
        $this->end_date = $end_date;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function isActive(): bool
    {
        $now = new DateTime();
        
        if ($this->start_date && $now < $this->start_date) {
            return false;
        }
        
        if ($this->end_date && $now > $this->end_date) {
            return false;
        }
        
        return true;
    }

    public function getDuration(): ?string
    {
        if ($this->start_date && $this->end_date) {
            $interval = $this->start_date->diff($this->end_date);
            return $interval->format('%a days');
        }
        
        return null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'doctor_id' => $this->doctor_id,
            'patient_id' => $this->patient_id,
            'medication_id' => $this->medication_id,
            'dosage' => $this->dosage,
            'instructions' => $this->instructions,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'is_active' => $this->isActive(),
            'duration' => $this->getDuration()
        ];
    }
}