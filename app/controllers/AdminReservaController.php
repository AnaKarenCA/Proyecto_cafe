<?php

require_once __DIR__ . '/../models/Reserva.php';

class AdminReservaController
{

    public function index()
    {
        $reservaModel = new Reserva();
        $reservas = $reservaModel->getAll();

        require __DIR__ . '/../views/admin/reservas/lista.php';
    }

}