<?php

require_once __DIR__ . '/../models/Pedido.php';

class AdminPedidoController
{

    public function index()
    {
        $pedidoModel = new Pedido();
        $pedidos = $pedidoModel->getAll();

        require __DIR__ . '/../views/admin/pedidos/lista.php';
    }

}