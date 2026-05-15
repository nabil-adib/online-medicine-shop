<?php

require_once __DIR__ . '/../models/Admin.php';

class AdminController
{
    private $adminModel;

    public function __construct($conn)
    {
        $this->adminModel = new Admin($conn);
    }

    // =========================
    // DASHBOARD
    // =========================
    public function dashboard()
    {
        $data = [
            'categoriesCount' => $this->adminModel->countCategories(),
            'medicinesCount'  => $this->adminModel->countMedicines(),
            'customersCount'  => $this->adminModel->countCustomers(),
            'ordersCount'     => $this->adminModel->countOrders(),
            'recentOrders'    => $this->adminModel->getRecentOrders()
        ];

        include __DIR__ . '/../views/admin/dashboard.php';
    }

    // =========================
    // CATEGORIES
    // =========================
    public function categories()
    {
        $categories = $this->adminModel->getAllCategories();

        include __DIR__ . '/../views/admin/categories.php';
    }

    public function addCategory()
    {
        include __DIR__ . '/../views/admin/add_category.php';
    }

    public function storeCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];

            $this->adminModel->insertCategory($name);

            header("Location: index.php?page=admin/categories");
            exit;
        }
    }

    public function deleteCategory()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $this->adminModel->deleteCategory($id);

            header("Location: index.php?page=admin/categories");
            exit;
        }
    }

    // =========================
    // MEDICINES
    // =========================
    public function medicines()
    {
        $medicines = $this->adminModel->getAllMedicines();

        include __DIR__ . '/../views/admin/medicines.php';
    }

    public function addMedicine()
    {
        $categories = $this->adminModel->getAllCategories();

        include __DIR__ . '/../views/admin/add_medicine.php';
    }

    public function storeMedicine()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'name'        => $_POST['name'],
                'category_id' => $_POST['category_id'],
                'vendor'      => $_POST['vendor_name'],
                'price'       => $_POST['price'],
                'stock'       => $_POST['stock'],
                'description' => $_POST['description']
            ];

            $this->adminModel->insertMedicine($data);

            header("Location: index.php?page=admin/medicines");
            exit;
        }
    }

    public function deleteMedicine()
    {
        if (isset($_GET['id'])) {
            $this->adminModel->deleteMedicine($_GET['id']);

            header("Location: index.php?page=admin/medicines");
            exit;
        }
    }

    // =========================
    // CUSTOMERS
    // =========================
    public function customers()
    {
        $customers = $this->adminModel->getAllCustomers();

        include __DIR__ . '/../views/admin/customers.php';
    }

    // =========================
    // ORDERS
    // =========================
    public function orders()
    {
        $orders = $this->adminModel->getAllOrders();

        include __DIR__ . '/../views/admin/orders.php';
    }

    public function updateOrderStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $orderId = $_POST['order_id'];
            $status  = $_POST['status'];

            $this->adminModel->updateOrderStatus($orderId, $status);

            header("Location: index.php?page=admin/orders");
            exit;
        }
    }
}