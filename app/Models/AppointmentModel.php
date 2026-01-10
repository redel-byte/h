<?php

class AppointmentModel extends BaseModel
{
    protected ?int $id = null;
    protected ?DateTime $date = null;
    protected ?DateTime $time = null;
    protected ?int $doctor_id = null;
    protected ?int $patient_id = null;
    protected ?string $reason = null;
    protected ?string $status = null;
    protected ?DateTime $created_at = null;
    protected ?DateTime $updated_at = null;

    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_DONE = 'done';
    public const STATUS_CANCELLED = 'cancelled';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    public function getTime(): ?DateTime
    {
        return $this->time;
    }

    public function setTime(DateTime $time): void
    {
        $this->time = $time;
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

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        if (in_array($status, [self::STATUS_SCHEDULED, self::STATUS_DONE, self::STATUS_CANCELLED])) {
            $this->status = $status;
        }
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

    public function isScheduled(): bool
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    public function isDone(): bool
    {
        return $this->status === self::STATUS_DONE;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function getDateTime(): ?DateTime
    {
        if ($this->date && $this->time) {
            $dateTime = clone $this->date;
            $dateTime->setTime(
                (int)$this->time->format('H'),
                (int)$this->time->format('i')
            );
            return $dateTime;
        }
        return null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date?->format('Y-m-d'),
            'time' => $this->time?->format('H:i:s'),
            'doctor_id' => $this->doctor_id,
            'patient_id' => $this->patient_id,
            'reason' => $this->reason,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s')
        ];
    }
}
