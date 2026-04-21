## CONFIG

## 1) Suggested Workspace Layout

- Keep source code in `app/` and public entry points in `public/`.
- Store helper scripts in `scripts/` and avoid runtime logic there.
- Place uploads in `uploads/` with strict write permissions.
- Use `.env` for secrets and `.env.example` for non-secret defaults.
- Keep local-only editor config under `.vscode/`.

## 2) Recommended VS Code Settings

```json
{
  "editor.formatOnSave": true,
  "editor.tabSize": 2,
  "editor.detectIndentation": true,
  "files.eol": "\n",
  "files.trimTrailingWhitespace": true,
  "files.insertFinalNewline": true,
  "search.exclude": {
    "**/node_modules": true,
    "**/vendor": true,
    "**/uploads": true
  },
  "files.associations": {
    "*.php": "php"
  }
}
```

## 3) Optional Local Tasks

```json
{
  "version": "2.0.0",
  "tasks": [
    {
      "label": "PHP Lint Current File",
      "type": "shell",
      "command": "php",
      "args": ["-l", "${file}"]
    },
    {
      "label": "Composer Validate",
      "type": "shell",
      "command": "composer",
      "args": ["validate", "--no-check-publish"]
    }
  ]
}
```

## 4) Runtime Configuration Notes

- Set timezone explicitly (for example, `Asia/Colombo`) in PHP config.
- Set UTF-8 collation in DB and ensure connection charset is UTF-8.
- Log errors to file in development and avoid displaying stack traces in production.
- Limit upload size at both PHP and web server levels.
- Separate dev/stage/prod DB credentials.

## 5) Environment Variable Conventions

- `APP_ENV=development`
- `APP_DEBUG=true`
- `APP_URL=http://localhost/Servo/public`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_NAME=servo_db`
- `DB_USER=root`
- `DB_PASS=`

## 6) Team Consistency Checklist

- Everyone uses the same PHP major/minor version locally.
- Composer lock file is committed and updated intentionally.
- SQL changes are documented before schema updates are applied.
- New folders and scripts are named consistently.
- Workspace settings do not include personal absolute paths.

---

# Unified Guide (PHP + HTML + JS)

This guide provides reusable, framework-agnostic implementation patterns for common "Task 1 + Task 2" style requests.

- Task 1 usually means: collect input + validate + save.
- Task 2 usually means: fetch + display/report.

All snippets are intentionally generic. Replace placeholders like `table_name`, `column_name`, `id`, and route URLs to match your project.

## Quick Usage Notes

1. Find your requirement in the task index.
2. Copy the relevant block.
3. Replace placeholder names consistently across HTML, JS, PHP, and SQL.
4. Keep both client-side and server-side validation for every business rule.
5. Use prepared statements for all DB writes and filtered reads.

## Assumptions

- Backend: PHP (PDO)
- Frontend: server-rendered HTML + vanilla JS
- DB: MySQL-compatible SQL syntax
- Existing DB connection object: `$pdo`
- Existing primary key column examples: `id`

---

## End-to-End Mini Example (Adapt Pattern Fast)

Scenario: Add `vehicle_color` field, save it, and display it in list and profile.

### 1) DB change

```sql
ALTER TABLE vehicles
ADD COLUMN vehicle_color VARCHAR(30) NULL;
```

### 2) Form input

```html
<label for="vehicle_color">Vehicle Color</label>
<input id="vehicle_color" name="vehicle_color" type="text" maxlength="30" required>
<small id="vehicle_color_error" class="error"></small>
```

### 3) Frontend validation

```html
<script>
document.querySelector('form').addEventListener('submit', function (e) {
  const color = document.getElementById('vehicle_color').value.trim();
  const err = document.getElementById('vehicle_color_error');
  err.textContent = '';

  if (!/^[A-Za-z ]{2,30}$/.test(color)) {
    e.preventDefault();
    err.textContent = 'Color must be 2-30 letters only.';
  }
});
</script>
```

### 4) Save logic (PHP)

```php
<?php
$vehicleColor = trim($_POST['vehicle_color'] ?? '');
if (!preg_match('/^[A-Za-z ]{2,30}$/', $vehicleColor)) {
    throw new InvalidArgumentException('Invalid vehicle color.');
}

$stmt = $pdo->prepare('UPDATE vehicles SET vehicle_color = :vehicle_color WHERE id = :id');
$stmt->execute([
    ':vehicle_color' => $vehicleColor,
    ':id' => (int)($_POST['id'] ?? 0),
]);
```

### 5) Retrieval + display

```php
<?php
$stmt = $pdo->prepare('SELECT id, name, vehicle_color FROM vehicles WHERE id = :id');
$stmt->execute([':id' => (int)($_GET['id'] ?? 0)]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<p>Vehicle Color: <?= htmlspecialchars($row['vehicle_color'] ?? '-') ?></p>
```

Use this same sequence for most add-field tasks.

---

## Task Index

1. Add new field and show in frontend page/card/detail
2. Add new field and show in table/list view
3. Add new field and show in profile section
4. Add new field and show in dashboard widget/card
5. Validate phone number
6. Validate NIC format
7. Validate age constraints
8. Enforce future/past date constraints
9. Validate uploaded file types (image/PDF)
10. Validate chassis number format
11. Conditional textarea requirement and limits
12. Dependent dropdowns (dynamic child options)
13. District search with partial matching
14. Status-based conditional fields
15. Dual phone inputs (country code + 9-digit local)
16. Stock-percentage graph
17. Summary card: highest value item
18. Replace percentage display with actual values
19. Calculate period in months from two dates
20. Pinned announcements with expiry + top ordering

---

## Standard Task Block Format

Use this structure for every implementation:

1. DB change
2. Form/input update
3. Validation rules (frontend + backend)
4. Save/update logic
5. Retrieval/display logic

For rules-only tasks, use:

1. Task 1: frontend guard
2. Task 2: backend enforcement

---

## 1) Add New Field and Show in Frontend Page/Card/Detail

### Task 1: Add field and save to DB

```sql
ALTER TABLE table_name
ADD COLUMN new_field VARCHAR(100) NULL;
```

```html
<input name="new_field" id="new_field" type="text" maxlength="100">
```

```php
<?php
$newField = trim($_POST['new_field'] ?? '');
$stmt = $pdo->prepare('UPDATE table_name SET new_field = :new_field WHERE id = :id');
$stmt->execute([
    ':new_field' => $newField,
    ':id' => (int)($_POST['id'] ?? 0),
]);
```

### Task 2: Retrieve and show on frontend

```php
<?php
$stmt = $pdo->prepare('SELECT id, title, new_field FROM table_name WHERE id = :id');
$stmt->execute([':id' => (int)($_GET['id'] ?? 0)]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="card-field">Value: <?= htmlspecialchars($item['new_field'] ?? '-') ?></div>
```

## 2) Add New Field and Show in Table/List View

### Task 1: Add and persist field

Use Task 1 from Section 1.

### Task 2: Add table column in UI

```php
<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>New Field</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $row): ?>
      <tr>
        <td><?= (int)$row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['new_field'] ?? '-') ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
```

## 3) Add New Field and Show in Profile Section

### Task 1: Persist profile field

```php
<?php
$bioTagline = trim($_POST['bio_tagline'] ?? '');
if (mb_strlen($bioTagline) > 120) {
    throw new InvalidArgumentException('Tagline max 120 characters.');
}

$stmt = $pdo->prepare('UPDATE users SET bio_tagline = :bio_tagline WHERE id = :id');
$stmt->execute([
    ':bio_tagline' => $bioTagline,
    ':id' => (int)$currentUserId,
]);
```

### Task 2: Show in profile

```php
<p class="profile-tagline"><?= htmlspecialchars($profile['bio_tagline'] ?? '-') ?></p>
```

## 4) Add New Field and Show in Dashboard Widget/Card

### Task 1: Save source data

Store field as in previous sections.

### Task 2: Aggregate and show widget value

```php
<?php
$stmt = $pdo->query('SELECT COUNT(*) AS total_with_value FROM table_name WHERE new_field IS NOT NULL AND new_field <> ""');
$metric = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="metric-card">
  <h4>Total With New Field</h4>
  <p><?= (int)$metric['total_with_value'] ?></p>
</div>
```

## 5) Validate Phone Number

### Task 1: Frontend validation

```html
<input id="phone" name="phone" type="tel" placeholder="07XXXXXXXX" required>
<script>
const phonePattern = /^07\d{8}$/;
document.querySelector('form').addEventListener('submit', function (e) {
  const v = document.getElementById('phone').value.trim();
  if (!phonePattern.test(v)) {
    e.preventDefault();
    alert('Phone must match 07XXXXXXXX.');
  }
});
</script>
```

### Task 2: Backend validation

```php
<?php
$phone = trim($_POST['phone'] ?? '');
if (!preg_match('/^07\d{8}$/', $phone)) {
    throw new InvalidArgumentException('Invalid phone format.');
}
```

## 6) Validate NIC Format

### Task 1: Frontend

```js
const nicPattern = /^([0-9]{9}[VXvx]|[0-9]{12})$/;
```

### Task 2: Backend

```php
<?php
$nic = strtoupper(trim($_POST['nic'] ?? ''));
if (!preg_match('/^([0-9]{9}V|[0-9]{12})$/', $nic)) {
    throw new InvalidArgumentException('Invalid NIC format.');
}
```

## 7) Validate Age Constraints

### Task 1: Frontend (derived age from DOB)

```js
function getAge(dob) {
  const d = new Date(dob);
  const diff = Date.now() - d.getTime();
  return new Date(diff).getUTCFullYear() - 1970;
}
```

```js
const age = getAge(document.getElementById('dob').value);
if (age < 18 || age > 70) {
  e.preventDefault();
  alert('Age must be between 18 and 70.');
}
```

### Task 2: Backend (must be authoritative)

```php
<?php
$dob = $_POST['dob'] ?? null;
$dobDate = new DateTimeImmutable($dob ?: '');
$today = new DateTimeImmutable('today');
$age = (int)$dobDate->diff($today)->y;

if ($age < 18 || $age > 70) {
    throw new InvalidArgumentException('Age must be between 18 and 70.');
}
```

## 8) Enforce Future/Past Date Constraints

### Task 1: Frontend min/max attributes

```html
<input type="date" id="start_date" name="start_date" min="<?= date('Y-m-d') ?>">
<input type="date" id="birth_date" name="birth_date" max="<?= date('Y-m-d') ?>">
```

### Task 2: Backend checks

```php
<?php
$today = new DateTimeImmutable('today');
$startDate = new DateTimeImmutable($_POST['start_date'] ?? '');
if ($startDate < $today) {
    throw new InvalidArgumentException('Start date cannot be in the past.');
}

$birthDate = new DateTimeImmutable($_POST['birth_date'] ?? '');
if ($birthDate > $today) {
    throw new InvalidArgumentException('Birth date cannot be in the future.');
}
```

## 9) Validate Uploaded File Types (Image/PDF)

### Task 1: Frontend input restrictions

```html
<input type="file" name="doc_file" accept="image/jpeg,image/png,application/pdf" required>
```

### Task 2: Backend MIME validation

```php
<?php
$allowed = ['image/jpeg', 'image/png', 'application/pdf'];
$tmpPath = $_FILES['doc_file']['tmp_name'] ?? '';
$mime = mime_content_type($tmpPath);

if (!in_array($mime, $allowed, true)) {
    throw new InvalidArgumentException('Only JPG, PNG, or PDF files are allowed.');
}
```

## 10) Validate Chassis Number Format

### Task 1: Frontend regex

```html
<input id="chassis_no" name="chassis_no" maxlength="17" required>
```

```js
const chassisPattern = /^[A-HJ-NPR-Z0-9]{17}$/;
```

### Task 2: Backend regex

```php
<?php
$chassisNo = strtoupper(trim($_POST['chassis_no'] ?? ''));
if (!preg_match('/^[A-HJ-NPR-Z0-9]{17}$/', $chassisNo)) {
    throw new InvalidArgumentException('Invalid chassis number format.');
}
```

## 11) Conditional Textarea Requirement and Limits

### Task 1: Frontend conditional behavior

```html
<select id="status" name="status">
  <option value="normal">Normal</option>
  <option value="rejected">Rejected</option>
</select>

<textarea id="reason" name="reason" maxlength="300"></textarea>
```

```js
document.querySelector('form').addEventListener('submit', function (e) {
  const status = document.getElementById('status').value;
  const reason = document.getElementById('reason').value.trim();
  if (status === 'rejected' && reason.length < 10) {
    e.preventDefault();
    alert('Reason is required (min 10 chars) when status is rejected.');
  }
});
```

### Task 2: Backend enforcement

```php
<?php
$status = $_POST['status'] ?? 'normal';
$reason = trim($_POST['reason'] ?? '');

if ($status === 'rejected' && mb_strlen($reason) < 10) {
    throw new InvalidArgumentException('Reason is required for rejected status.');
}

if (mb_strlen($reason) > 300) {
    throw new InvalidArgumentException('Reason cannot exceed 300 characters.');
}
```

## 12) Dependent Dropdowns (Dynamic Child Options)

### Task 1: Frontend dynamic load

```html
<select id="province_id" name="province_id"></select>
<select id="district_id" name="district_id"></select>
```

```js
document.getElementById('province_id').addEventListener('change', async function () {
  const provinceId = this.value;
  const res = await fetch('/api/districts?province_id=' + encodeURIComponent(provinceId));
  const districts = await res.json();

  const districtEl = document.getElementById('district_id');
  districtEl.innerHTML = '<option value="">Select district</option>';
  districts.forEach(function (d) {
    districtEl.insertAdjacentHTML('beforeend', '<option value="' + d.id + '">' + d.name + '</option>');
  });
});
```

### Task 2: Backend endpoint

```php
<?php
$provinceId = (int)($_GET['province_id'] ?? 0);
$stmt = $pdo->prepare('SELECT id, name FROM districts WHERE province_id = :province_id ORDER BY name');
$stmt->execute([':province_id' => $provinceId]);
header('Content-Type: application/json');
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
```

## 13) District Search With Partial Matching

### Task 1: Frontend search input

```html
<input type="text" name="district_q" placeholder="Search district">
```

### Task 2: Backend LIKE query

```php
<?php
$q = trim($_GET['district_q'] ?? '');
$stmt = $pdo->prepare('SELECT id, name FROM districts WHERE name LIKE :q ORDER BY name LIMIT 25');
$stmt->execute([':q' => '%' . $q . '%']);
$districts = $stmt->fetchAll(PDO::FETCH_ASSOC);
```

## 14) Status-Based Conditional Fields

### Task 1: Frontend show/hide and requirement

```js
const statusEl = document.getElementById('status');
const followupWrap = document.getElementById('followup_wrap');
const followupInput = document.getElementById('followup_date');

function syncFollowup() {
  const required = statusEl.value === 'pending_followup';
  followupWrap.style.display = required ? 'block' : 'none';
  followupInput.required = required;
}

statusEl.addEventListener('change', syncFollowup);
syncFollowup();
```

### Task 2: Backend rule

```php
<?php
$status = $_POST['status'] ?? '';
$followupDate = trim($_POST['followup_date'] ?? '');
if ($status === 'pending_followup' && $followupDate === '') {
    throw new InvalidArgumentException('Follow-up date is required for pending follow-up status.');
}
```

## 15) Dual Phone Inputs (Country Code + 9-Digit Local)

### Task 1: Frontend structure and check

```html
<input name="country_code" id="country_code" value="+94" maxlength="4" required>
<input name="local_phone" id="local_phone" maxlength="9" placeholder="XXXXXXXXX" required>
```

```js
const ccOk = /^\+[1-9]\d{0,3}$/.test(document.getElementById('country_code').value.trim());
const localOk = /^\d{9}$/.test(document.getElementById('local_phone').value.trim());
if (!ccOk || !localOk) {
  e.preventDefault();
  alert('Use valid country code and 9-digit local phone number.');
}
```

### Task 2: Backend compose and save

```php
<?php
$countryCode = trim($_POST['country_code'] ?? '');
$localPhone = trim($_POST['local_phone'] ?? '');

if (!preg_match('/^\+[1-9]\d{0,3}$/', $countryCode) || !preg_match('/^\d{9}$/', $localPhone)) {
    throw new InvalidArgumentException('Invalid phone parts.');
}

$fullPhone = $countryCode . $localPhone;
$stmt = $pdo->prepare('UPDATE contacts SET phone_full = :phone_full WHERE id = :id');
$stmt->execute([':phone_full' => $fullPhone, ':id' => (int)($_POST['id'] ?? 0)]);
```

## 16) Stock-Percentage Graph

### Task 1: Backend data prep

```php
<?php
$sql = 'SELECT item_name, in_stock, total_capacity,
               ROUND((in_stock / NULLIF(total_capacity, 0)) * 100, 2) AS stock_pct
        FROM inventory
        ORDER BY item_name';
$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
```

### Task 2: Frontend chart render (generic)

```html
<canvas id="stockChart"></canvas>
<script>
const labels = <?= json_encode(array_column($rows, 'item_name')) ?>;
const values = <?= json_encode(array_column($rows, 'stock_pct')) ?>;
// Plug labels/values into your chart library (Chart.js, ApexCharts, ECharts, etc.)
</script>
```

## 17) Summary Card: Highest Value Item

### Task 1: Backend highest metric query

```php
<?php
$sql = 'SELECT item_name, metric_value
        FROM metrics_table
        ORDER BY metric_value DESC
        LIMIT 1';
$top = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
```

### Task 2: Frontend card

```php
<div class="summary-card">
  <h4>Highest Value Item</h4>
  <p><?= htmlspecialchars($top['item_name'] ?? '-') ?></p>
  <strong><?= htmlspecialchars($top['metric_value'] ?? '0') ?></strong>
</div>
```

## 18) Replace Percentage Display With Actual Values

### Task 1: Backend select actuals

```php
<?php
$sql = 'SELECT item_name, sold_units, total_units FROM sales_summary';
$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
```

### Task 2: Frontend output actual values

```php
<?php foreach ($rows as $row): ?>
  <p>
    <?= htmlspecialchars($row['item_name']) ?>:
    <?= (int)$row['sold_units'] ?> / <?= (int)$row['total_units'] ?>
  </p>
<?php endforeach; ?>
```

## 19) Calculate Time Period in Months From Two Dates

### Task 1: Backend derivation

```php
<?php
$start = new DateTimeImmutable($row['start_date']);
$end = new DateTimeImmutable($row['end_date']);
$diff = $start->diff($end);
$months = ($diff->y * 12) + $diff->m;
if ($diff->d > 0) {
    $months += 1; // Optional rounding up partial month
}
```

### Task 2: Display derived period

```php
<span><?= (int)$months ?> month(s)</span>
```

## 20) Pinned Announcements With Expiry + Top Ordering

### Task 1: DB and save rules

```sql
ALTER TABLE announcements
ADD COLUMN is_pinned TINYINT(1) NOT NULL DEFAULT 0,
ADD COLUMN pin_expires_at DATETIME NULL;
```

```php
<?php
$isPinned = !empty($_POST['is_pinned']) ? 1 : 0;
$pinExpiresAt = $_POST['pin_expires_at'] ?? null;

if ($isPinned && empty($pinExpiresAt)) {
    throw new InvalidArgumentException('Pin expiry is required when announcement is pinned.');
}

$stmt = $pdo->prepare(
    'UPDATE announcements
     SET is_pinned = :is_pinned, pin_expires_at = :pin_expires_at
     WHERE id = :id'
);
$stmt->execute([
    ':is_pinned' => $isPinned,
    ':pin_expires_at' => $pinExpiresAt,
    ':id' => (int)($_POST['id'] ?? 0),
]);
```

### Task 2: Retrieval order and expiry handling

```php
<?php
$sql = 'SELECT id, title, body, is_pinned, pin_expires_at, created_at
        FROM announcements
        WHERE pin_expires_at IS NULL OR pin_expires_at >= NOW()
        ORDER BY is_pinned DESC, created_at DESC';
$announcements = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
```

Optional cleanup approaches:

1. DB event/cron to unpin expired rows.
2. Lazy cleanup during reads:

```sql
UPDATE announcements
SET is_pinned = 0
WHERE is_pinned = 1
  AND pin_expires_at IS NOT NULL
  AND pin_expires_at < NOW();
```

---

## Traceability Checklist

Use this matrix to confirm each original request line is covered once.

| Request Pattern | Covered In |
| --- | --- |
| Add field + save + show frontend | Section 1 |
| Add field + show table | Section 2 |
| Add field + show profile | Section 3 |
| Add field + show dashboard | Section 4 |
| Phone validation | Section 5 |
| NIC validation | Section 6 |
| Age validation | Section 7 |
| Future/past date validation | Section 8 |
| File type rules image/PDF | Section 9 |
| Chassis format validation | Section 10 |
| Conditional textarea rules | Section 11 |
| Dependent dropdown behavior | Section 12 |
| District partial search | Section 13 |
| Status-based conditional fields | Section 14 |
| Country code + 9-digit phone | Section 15 |
| Stock percentage graph | Section 16 |
| Summary highest value card | Section 17 |
| Percentage replaced by actuals | Section 18 |
| Time period in months | Section 19 |
| Announcement pinning + expiry + top order | Section 20 |

---

## Consistency Checklist Before Integrating

1. Input `name` attributes match backend `$_POST` keys.
2. Backend regex/error messages match frontend checks.
3. SQL placeholders (`:field`) match execute array keys.
4. Display keys match SELECT aliases.
5. Unknown schema names are replaced consistently in every snippet.

## Non-Goals of This Document

- No direct edits to controllers/models/views.
- No migration execution commands.
- No project-specific routing refactor.

This README is a practical implementation playbook you can adapt per module.