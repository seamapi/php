<?php

namespace Seam;

class Device {
  public string $device_id;
  public string $workspace_id;
  public mixed $properties;
  public mixed $location;
  public string $created_at;
  public mixed $errors;
}

class Workspace {
  public string $workspace_id;
  public string $created_at;
}