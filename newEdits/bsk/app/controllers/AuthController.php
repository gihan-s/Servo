<?php
namespace App\Controllers;

use Core\Controller;
use Core\Database;

class AuthController extends Controller
{
    use UploadHelper; // enable saveUpload trait methods
    public function showLogin(): void
    {
        $this->view('auth/login');
    }

    public function showRegister(): void
    {
    $pdo = Database::getConnection();
    // Uniqueness preload
    $clientEmails = $pdo->query('SELECT Email FROM Client')->fetchAll(\PDO::FETCH_COLUMN) ?: [];
    $providerRows = $pdo->query('SELECT Email, NIC_No FROM Provider')->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    $providerEmails = $providerNics = [];
    foreach ($providerRows as $r) { if ($r['Email']) $providerEmails[] = $r['Email']; if ($r['NIC_No']) $providerNics[] = $r['NIC_No']; }
    // Meta data for provider category section
    $categories = $pdo->query('SELECT Category_ID, Name FROM Category ORDER BY Name')->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    $skills = $pdo->query('SELECT Skill_ID, Skill FROM Skills ORDER BY Skill')->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    $locations = $pdo->query('SELECT Location_ID, District_ID, City FROM Location ORDER BY City')->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    $districts = $pdo->query('SELECT ID, District FROM Districts ORDER BY District')->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    $this->view('auth/registration', compact('clientEmails','providerEmails','providerNics','categories','skills','locations','districts'));
    }

    public function login(): void
    {
        session_start();
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $type = ($_POST['type'] ?? 'client') === 'provider' ? 'provider' : 'client';
        $_SESSION['login_type'] = $type; // remember toggle choice

        if ($email === '' || $password === '') {
            $_SESSION['login_error'] = 'Please enter email and password.';
            $this->redirect('/login');
        }

        $pdo = Database::getConnection();
        if ($type === 'provider') {
            $stmt = $pdo->prepare('SELECT Provider_ID AS id, Password, Approvel_Status FROM Provider WHERE Email = ? LIMIT 1');
            $stmt->execute([$email]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$row) { $_SESSION['login_error'] = 'Email not registered (provider).'; $this->redirect('/login'); return; }
            if (!password_verify($password, $row['Password'])) { $_SESSION['login_error'] = 'Incorrect password.'; $this->redirect('/login'); return; }
            if (strcasecmp($row['Approvel_Status'] ?? '', 'pending') === 0) { $_SESSION['login_error'] = 'Your provider account is pending approval.'; $this->redirect('/login'); return; }
            // success
            $_SESSION['user_id'] = (int)$row['id'];
            $_SESSION['role'] = 'provider';
            $this->redirect('/client/dashboard');
        } else { // client
            $stmt = $pdo->prepare('SELECT Client_ID AS id, Password FROM Client WHERE Email = ? LIMIT 1');
            $stmt->execute([$email]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$row) { $_SESSION['login_error'] = 'Email not registered (client).'; $this->redirect('/login'); return; }
            if (!password_verify($password, $row['Password'])) { $_SESSION['login_error'] = 'Incorrect password.'; $this->redirect('/login'); return; }
            $_SESSION['user_id'] = (int)$row['id'];
            $_SESSION['role'] = 'client';
            $this->redirect('/client/dashboard');
        }
    }

    public function register(): void
    {
        // Pure PHP (non-AJAX) registration handling with validation & redirect (PRG pattern)
        session_start();

        $type      = ($_POST['user_type'] ?? 'client') === 'provider' ? 'provider' : 'client';
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName  = trim($_POST['last_name'] ?? '');
        $gender    = trim($_POST['gender'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $contact   = trim($_POST['contact_no'] ?? '');
        $password  = $_POST['password'] ?? '';
        $repassword= $_POST['repassword'] ?? '';
        $bio       = trim($_POST['bio'] ?? '');
        $website   = trim($_POST['website'] ?? '');
        $nic       = trim($_POST['nic_no'] ?? '');

        $errors = [];

        $pdo = Database::getConnection();

        // Email + (for providers) NIC uniqueness within relevant table only
        if (!$errors) {
            if ($type === 'provider') {
                $stmt = $pdo->prepare('SELECT 1 FROM Provider WHERE Email = ? LIMIT 1');
                $stmt->execute([$email]);
                if ($stmt->fetch()) { $errors[] = 'Email is already registered (provider).'; }
                if ($nic !== '') {
                    $stmt = $pdo->prepare('SELECT 1 FROM Provider WHERE NIC_No = ? LIMIT 1');
                    $stmt->execute([$nic]);
                    if ($stmt->fetch()) { $errors[] = 'NIC is already registered.'; }
                }
            } else { // client
                $stmt = $pdo->prepare('SELECT 1 FROM Client WHERE Email = ? LIMIT 1');
                $stmt->execute([$email]);
                if ($stmt->fetch()) { $errors[] = 'Email is already registered (client).'; }
            }
        }

        if ($errors) {
            $_SESSION['reg_errors'] = $errors;
            $_SESSION['reg_old'] = [
                'user_type' => $type,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'gender' => $gender,
                'email' => $email,
                'contact_no' => $contact,
                'bio' => $bio,
                'website' => $website,
                'nic_no' => $nic,
            ];
            $this->redirect('/register');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $pdo->beginTransaction();
            if ($type === 'provider') {
                // Insert provider with pending approval status
                $stmt = $pdo->prepare('INSERT INTO Provider (Email, Contact_No, NIC_No, Password, Created_At, First_Name, Last_Name, Gender, Bio, Website, Approvel_Status) VALUES (?,?,?,?,NOW(),?,?,?,?,?,?)');
                $stmt->execute([$email, $contact, $nic, $hash, $firstName, $lastName, $gender, $bio, $website, 'pending']);
                $userId = (int)$pdo->lastInsertId();

                $uploadBase = dirname(__DIR__, 2) . '/public/uploads/providers/' . $userId . '/';
                if (!is_dir($uploadBase)) { @mkdir($uploadBase, 0777, true); }
                $this->saveUpload('profile', 'profile_picture', $uploadBase, $pdo, 'Provider', 'Profile_Picture', $userId);
                $this->saveUpload('nic_front', 'nic_front', $uploadBase, $pdo, 'Provider', 'NIC_Front', $userId);
                $this->saveUpload('nic_back', 'nic_back', $uploadBase, $pdo, 'Provider', 'NIC_Back', $userId);
                $this->saveUpload('resume', 'resume', $uploadBase, $pdo, 'Provider', 'Resume', $userId);

                // Handle provider categories JSON if provided
                if (!empty($_POST['provider_categories_json'])) {
                    $json = json_decode($_POST['provider_categories_json'], true);
                    if (is_array($json)) {
                        // Pre-fetch max IDs for provider_categories and provider_categories_has_location since no AUTO_INCREMENT
                        $maxCatId = (int)$pdo->query('SELECT COALESCE(MAX(ID),0) FROM provider_categories')->fetchColumn();
                        $maxLocLinkId = (int)$pdo->query('SELECT COALESCE(MAX(ID),0) FROM provider_categories_has_location')->fetchColumn();
                        $catStmt = $pdo->prepare('INSERT INTO provider_categories (ID, Category_ID, Provider_ID, Title, Description, Default_Price) VALUES (?,?,?,?,?,?)');
                        $skillStmt = $pdo->prepare('INSERT INTO provider_categories_has_skills (Provider_Categories_ID, Skills_Skill_ID) VALUES (?,?)');
                        $locStmt = $pdo->prepare('INSERT INTO provider_categories_has_location (Provider_Categories_ID, Location_Location_ID, ID, District_ID) VALUES (?,?,?,?)');
                        $locByDistrictStmt = $pdo->prepare('SELECT Location_ID, District_ID FROM Location WHERE District_ID = ?');
                        $locMetaStmt = $pdo->prepare('SELECT District_ID FROM Location WHERE Location_ID = ?');
                        foreach ($json as $entry) {
                            $categoryId = (int)($entry['category_id'] ?? 0);
                            if ($categoryId <= 0) { continue; }
                            $title = substr(trim($entry['title'] ?? ''),0,100);
                            $desc = substr(trim($entry['description'] ?? ''),0,1024);
                            $price = is_numeric($entry['price'] ?? null) ? (float)$entry['price'] : null;
                            $catId = ++$maxCatId;
                            $catStmt->execute([$catId, $categoryId, $userId, $title, $desc, $price]);
                            // Skills
                            if (!empty($entry['skills']) && is_array($entry['skills'])) {
                                foreach ($entry['skills'] as $skillId) {
                                    $skillId = (int)$skillId; if ($skillId>0) { $skillStmt->execute([$catId, $skillId]); }
                                }
                            }
                            // Locations / Districts
                            if (!empty($entry['locations']) && is_array($entry['locations'])) {
                                foreach ($entry['locations'] as $locSel) {
                                    if (!is_array($locSel) || empty($locSel['type']) || empty($locSel['id'])) { continue; }
                                    $type = $locSel['type']; $id = (int)$locSel['id']; if ($id<=0) continue;
                                    if ($type === 'dist') {
                                        $locByDistrictStmt->execute([$id]);
                                        $rows = $locByDistrictStmt->fetchAll(\PDO::FETCH_ASSOC);
                                        foreach ($rows as $row) {
                                            $maxLocLinkId++; $locStmt->execute([$catId, (int)$row['Location_ID'], $maxLocLinkId, (int)$row['District_ID']]);
                                        }
                                    } elseif ($type === 'loc') {
                                        $locMetaStmt->execute([$id]);
                                        $districtId = (int)$locMetaStmt->fetchColumn();
                                        if ($districtId>0) { $maxLocLinkId++; $locStmt->execute([$catId, $id, $maxLocLinkId, $districtId]); }
                                    }
                                }
                            }
                        }
                    }
                }
            } else { // client
                $stmt = $pdo->prepare('INSERT INTO Client (Email, Contact_No, Password, Created_At, First_Name, Last_Name, Gender, Bio, Social_Link) VALUES (?,?,?,?,?,?,?,?,?)');
                $stmt->execute([$email, $contact, $hash, date('Y-m-d H:i:s'), $firstName, $lastName, $gender, $bio, $website]);
                $userId = (int)$pdo->lastInsertId();

                $uploadBase = dirname(__DIR__, 2) . '/public/uploads/clients/' . $userId . '/';
                if (!is_dir($uploadBase)) { @mkdir($uploadBase, 0777, true); }
                $this->saveUpload('profile', 'profile_picture', $uploadBase, $pdo, 'Client', 'Profile_Picture', $userId);
            }
            $pdo->commit();
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) { $pdo->rollBack(); }
            $_SESSION['reg_errors'] = ['Registration failed.'];
            $_SESSION['reg_old'] = [
                'user_type' => $type,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'gender' => $gender,
                'email' => $email,
                'contact_no' => $contact,
                'bio' => $bio,
                'website' => $website,
                'nic_no' => $nic,
            ];
            $this->redirect('/register');
        }

    // Success -> set flash to show approval pending notice after redirect to login
    $_SESSION['reg_pending_notice'] = 1;
    $this->redirect('/login?registered=1');
    }

    public function logout(): void
    {
        session_start();
        session_destroy();
        header('Location: /login');
    }
}

namespace App\Controllers;

trait UploadHelper
{
    private function saveUpload(string $key, string $inputName, string $directory, \PDO $pdo, string $table, string $column, int $id): void
    {
        if (!isset($_FILES[$inputName]) || !is_uploaded_file($_FILES[$inputName]['tmp_name'])) {
            return;
        }
        $name = basename($_FILES[$inputName]['name']);
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $safe = $key . '_' . time() . '.' . strtolower($ext);
        $dest = rtrim($directory, '/\\') . '/' . $safe;
        if (!is_dir($directory)) { @mkdir($directory, 0777, true); }
        if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $dest)) {
            $relative = '/uploads/' . ($table === 'Provider' ? 'providers' : 'clients') . '/' . $id . '/' . $safe;
            $stmt = $pdo->prepare("UPDATE $table SET $column = ? WHERE " . ($table === 'Provider' ? 'Provider_ID' : 'Client_ID') . ' = ?');
            $stmt->execute([$relative, $id]);
        }
    }
}


