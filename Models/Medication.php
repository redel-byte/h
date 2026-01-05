<?php

class Medication extends BaseModel
{
    protected ?int $id = null;
    protected ?string $name = null;
    protected ?string $description = null;
    protected ?string $dosage_form = null;
    protected ?string $strength = null;
    protected ?string $manufacturer = null;
    protected ?bool $active = true;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDosageForm(): ?string
    {
        return $this->dosage_form;
    }

    public function setDosageForm(string $dosage_form): void
    {
        $this->dosage_form = $dosage_form;
    }

    public function getStrength(): ?string
    {
        return $this->strength;
    }

    public function setStrength(string $strength): void
    {
        $this->strength = $strength;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(string $manufacturer): void
    {
        $this->manufacturer = $manufacturer;
    }

    public function isActive(): bool
    {
        return $this->active ?? true;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
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

    public function getFullName(): string
    {
        $name = $this->name ?? '';
        $strength = $this->strength ? ' ' . $this->strength : '';
        $form = $this->dosage_form ? ' ' . $this->dosage_form : '';
        
        return trim($name . $strength . $form);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'dosage_form' => $this->dosage_form,
            'strength' => $this->strength,
            'manufacturer' => $this->manufacturer,
            'active' => $this->isActive(),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'full_name' => $this->getFullName()
        ];
    }
}