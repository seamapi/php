<?php

namespace Seam\Routes\Clients;

use Seam\Seam;

class Acs
{
    private Seam $seam;
    public AcsAccessGroups $access_groups;
    public AcsCredentials $credentials;
    public AcsEncoders $encoders;
    public AcsEntrances $entrances;
    public AcsSystems $systems;
    public AcsUsers $users;
    public function __construct(Seam $seam)
    {
        $this->seam = $seam;
        $this->access_groups = new AcsAccessGroups($seam);
        $this->credentials = new AcsCredentials($seam);
        $this->encoders = new AcsEncoders($seam);
        $this->entrances = new AcsEntrances($seam);
        $this->systems = new AcsSystems($seam);
        $this->users = new AcsUsers($seam);
    }
}
