<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\EmployeeUpload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    private Employee $me;

    protected function setUp(): void
    {
        parent::setUp();

        $this->me = Employee::factory()->create([
            'employee_id' => 10001,
            'password' => Hash::make('secret1'),
        ]);
    }

    protected function tearDown(): void
    {
        // Remove photos written by the upload tests
        foreach (File::glob(public_path('photo/1*_test-photo.*')) as $file) {
            File::delete($file);
        }
        parent::tearDown();
    }

    private function loggedIn(): static
    {
        return $this->withSession(['employee' => [
            'id' => $this->me->id,
            'employee_id' => $this->me->employee_id,
            'employee_name' => $this->me->employee_name,
        ]]);
    }

    private function validEmployeeData(array $overrides = []): array
    {
        return array_merge([
            'employee_code' => 'EMP-NEW',
            'employee_name' => 'Mg Mg',
            'nrc_number' => '12/abc(N)123456',
            'password' => 'pass1234',
            'email_address' => 'mgmg@example.com',
            'gender' => 1,
            'date_of_birth' => '1995-05-05',
            'marital_status' => 0,
            'address' => 'Yangon',
        ], $overrides);
    }

    public function test_login_page_is_shown(): void
    {
        $this->get('/')->assertOk();
        $this->get('/login')->assertOk();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/employees/lists')->assertRedirect(route('login'));
    }

    public function test_login_with_wrong_password_fails(): void
    {
        $this->post('/employees/check', ['employee_id' => 10001, 'emp_password' => 'wrong'])
            ->assertRedirect(route('login'));
        $this->assertNull(session('employee'));
    }

    public function test_login_keeps_no_password_in_session(): void
    {
        $this->post('/employees/check', ['employee_id' => 10001, 'emp_password' => 'secret1'])
            ->assertRedirect(route('employees.lists'));

        $this->assertSame($this->me->id, session('employee.id'));
        $this->assertArrayNotHasKey('password', session('employee'));
    }

    public function test_inactivated_employee_is_logged_out(): void
    {
        $this->me->delete();
        $this->loggedIn()->get('/employees/lists')->assertRedirect(route('login'));
    }

    public function test_logout_is_post_only(): void
    {
        $this->loggedIn()->get('/logout')->assertStatus(405);
        $this->loggedIn()->post('/logout')->assertRedirect(route('login'));
        $this->assertNull(session('employee'));
    }

    public function test_list_and_search(): void
    {
        Employee::factory()->create(['employee_name' => 'Aung Aung']);
        Employee::factory()->create(['employee_name' => 'Su Su']);

        $this->loggedIn()->get('/employees/lists')
            ->assertOk()->assertSee('Aung Aung')->assertSee('Su Su');

        $this->loggedIn()->get('/employees/searchs?employeeName=Aung')
            ->assertOk()->assertSee('Aung Aung')->assertDontSee('Su Su');
    }

    public function test_list_paginates_with_bootstrap_links(): void
    {
        Employee::factory()->count(25)->create();
        $this->loggedIn()->get('/employees/lists')->assertOk()->assertSee('page-link', false);
    }

    public function test_register_without_photo(): void
    {
        $this->loggedIn()->post('/employees/store', $this->validEmployeeData())
            ->assertRedirect(route('employees.lists'))
            ->assertSessionHas('status');

        $employee = Employee::where('email_address', 'mgmg@example.com')->firstOrFail();
        $this->assertSame(10002, (int) $employee->employee_id);
        $this->assertNull($employee->marital_status); // 0 = not selected
        $this->assertTrue(Hash::check('pass1234', $employee->password));
    }

    public function test_register_with_photo_uses_safe_file_name(): void
    {
        $photo = UploadedFile::fake()->image('../Test Photo.jpg');

        $this->loggedIn()->post('/employees/store', $this->validEmployeeData(['photo' => $photo]))
            ->assertRedirect(route('employees.lists'));

        $upload = EmployeeUpload::firstOrFail();
        $this->assertSame('10002_test-photo.jpg', $upload->file_name);
        $this->assertFileExists(public_path('photo/10002_test-photo.jpg'));
    }

    public function test_update_uses_id_from_url_and_replaces_photo(): void
    {
        $other = Employee::factory()->create();
        $target = Employee::factory()->create();

        // first photo
        $this->loggedIn()->put("/employees/update/{$target->id}", $this->validEmployeeData([
            'email_address' => $target->email_address,
            'photo' => UploadedFile::fake()->image('test-photo.png'),
        ]));
        $this->assertCount(1, EmployeeUpload::where('employee_id', $target->employee_id)->get());

        // second photo replaces the first; a tampered hidden "id" must be ignored
        $this->loggedIn()->put("/employees/update/{$target->id}", $this->validEmployeeData([
            'id' => $other->id,
            'employee_name' => 'Updated Name',
            'email_address' => $target->email_address,
            'photo' => UploadedFile::fake()->image('test-photo.jpg'),
        ]))->assertRedirect(route('employees.lists'))->assertSessionHas('status');

        $this->assertSame('Updated Name', $target->fresh()->employee_name);
        $this->assertNotSame('Updated Name', $other->fresh()->employee_name);
        $uploads = EmployeeUpload::where('employee_id', $target->employee_id)->get();
        $this->assertCount(1, $uploads);
        $this->assertSame('jpg', $uploads->first()->file_extension);
    }

    public function test_inactivate_and_activate(): void
    {
        $other = Employee::factory()->create();

        $this->loggedIn()->post("/employees/inactive/{$other->id}")
            ->assertSessionHas('status', 'Employee Inactivated successfully.');
        $this->assertSoftDeleted($other);

        $this->loggedIn()->post("/employees/active/{$other->id}")
            ->assertSessionHas('status', 'Employee Activated successfully.');
        $this->assertNotSoftDeleted($other);
    }

    public function test_cannot_delete_yourself_but_can_delete_others(): void
    {
        $other = Employee::factory()->create();

        $this->loggedIn()->delete("/employees/delete/{$this->me->id}")->assertSessionHas('error_message');
        $this->assertModelExists($this->me);

        $this->loggedIn()->delete("/employees/delete/{$other->id}")->assertSessionHas('status');
        $this->assertModelMissing($other);
    }

    public function test_pdf_and_excel_downloads(): void
    {
        Employee::factory()->count(3)->create();

        $pdf = $this->loggedIn()->get('/employees/pdf-search-downloads');
        $pdf->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $pdf->getContent());

        $this->loggedIn()->get('/employees/excel-downloads')->assertOk()->assertDownload('search_employees_results.xlsx');
        $this->loggedIn()->get('/employees/export-excels')->assertOk()->assertDownload('employee_registration_form.xlsx');
    }

    public function test_excel_download_with_no_results_shows_error(): void
    {
        $this->loggedIn()->from('/employees/lists')->get('/employees/excel-downloads?employeeName=nobody-has-this-name')
            ->assertRedirect('/employees/lists')->assertSessionHas('error_message');
    }

    private function makeImportFile(array $rows): UploadedFile
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['Employee Code', 'Employee Name', 'NRC Number', 'Password', 'Email Address', 'Gender', 'Date of Birth', 'Marital Status', 'Address'], null, 'A1');
        $sheet->fromArray($rows, null, 'A2');

        $path = tempnam(sys_get_temp_dir(), 'xlsx');
        (new Xlsx($spreadsheet))->save($path);

        return new UploadedFile($path, 'employee_registration_form.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
    }

    public function test_excel_import_keeps_each_rows_birth_date(): void
    {
        $file = $this->makeImportFile([
            ['C1', 'First', '1/abc(N)111111', 'pass1', 'first@example.com', 1, Date::PHPToExcel(new \DateTime('1990-01-01')), 1, 'Addr 1'],
            ['C2', 'Second', '1/abc(N)222222', 'pass2', 'second@example.com', 2, Date::PHPToExcel(new \DateTime('1985-12-31')), 2, 'Addr 2'],
        ]);

        $this->loggedIn()->from('/employees/register/excel-registers')
            ->post('/employees/import-excels', ['excelFile' => $file])
            ->assertSessionHas('status', '2 employee(s) imported successfully');

        $this->assertSame('1990-01-01', substr(Employee::where('email_address', 'first@example.com')->value('date_of_birth'), 0, 10));
        $this->assertSame('1985-12-31', substr(Employee::where('email_address', 'second@example.com')->value('date_of_birth'), 0, 10));
    }

    public function test_excel_import_with_errors_saves_nothing(): void
    {
        $file = $this->makeImportFile([
            ['C1', 'First', '1/abc(N)111111', 'pass1', 'dup@example.com', 1, Date::PHPToExcel(new \DateTime('1990-01-01')), 1, 'Addr'],
            ['C2', 'Second', '1/abc(N)222222', 'pass2', 'dup@example.com', 1, Date::PHPToExcel(new \DateTime('1990-01-01')), 1, 'Addr'],
        ]);

        $this->loggedIn()->from('/employees/register/excel-registers')
            ->post('/employees/import-excels', ['excelFile' => $file])
            ->assertSessionHas('validation_import_error');

        $this->assertSame(1, Employee::count()); // only "me"
    }
}
