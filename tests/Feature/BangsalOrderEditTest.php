<?php

namespace Tests\Feature;

use App\Models\Bangsal;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BangsalOrderEditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed some wards if not present
        Bangsal::factory()->create(['nama_bangsal' => 'Mawar']);
        Bangsal::factory()->create(['nama_bangsal' => 'Melati']);
    }

    public function test_edit_page_requires_authentication(): void
    {
        $order = Order::factory()->create();

        $response = $this->get(route('bangsal.orders.edit', $order));

        $response->assertRedirect(route('login'));
    }

    public function test_edit_page_requires_correct_ward(): void
    {
        $bangsal1 = Bangsal::where('nama_bangsal', 'Mawar')->first();
        $bangsal2 = Bangsal::where('nama_bangsal', 'Melati')->first();

        $user1 = User::factory()->create([
            'role' => 'bangsal',
            'bangsal_id' => $bangsal1->id,
        ]);

        $orderOfWard2 = Order::factory()->create([
            'bangsal_id' => $bangsal2->id,
        ]);

        $response = $this->actingAs($user1)
            ->get(route('bangsal.orders.edit', $orderOfWard2));

        $response->assertStatus(403);
    }

    public function test_edit_page_loads_successfully_for_authorized_ward(): void
    {
        $bangsal = Bangsal::where('nama_bangsal', 'Mawar')->first();

        $user = User::factory()->create([
            'role' => 'bangsal',
            'bangsal_id' => $bangsal->id,
        ]);

        $order = Order::factory()->create([
            'bangsal_id' => $bangsal->id,
            'created_by' => $user->id,
        ]);

        $patient = Patient::factory()->create([
            'bangsal_id' => $bangsal->id,
        ]);

        $detail = OrderDetail::factory()->create([
            'order_id' => $order->id,
            'patient_id' => $patient->id,
            'nasi' => true,
            'bubur' => false,
        ]);

        $response = $this->actingAs($user)
            ->get(route('bangsal.orders.edit', $order));

        $response->assertStatus(200);
        $response->assertViewIs('bangsal.form-input');
        $response->assertViewHas('order');
    }

    public function test_update_order_requires_correct_ward(): void
    {
        $bangsal1 = Bangsal::where('nama_bangsal', 'Mawar')->first();
        $bangsal2 = Bangsal::where('nama_bangsal', 'Melati')->first();

        $user1 = User::factory()->create([
            'role' => 'bangsal',
            'bangsal_id' => $bangsal1->id,
        ]);

        $orderOfWard2 = Order::factory()->create([
            'bangsal_id' => $bangsal2->id,
        ]);

        $response = $this->actingAs($user1)
            ->put(route('bangsal.orders.update', $orderOfWard2), [
                'pasiens' => [
                    [
                        'nama_pasien' => 'Budi',
                        'no_rm' => '123456',
                        'kamar_kelas' => 'Kamar 10A',
                        'bentuk_makanan' => ['Nasi'],
                        'diet' => 'RG',
                        'keterangan' => 'Alergi kacang',
                    ]
                ]
            ]);

        $response->assertStatus(403);
    }

    public function test_update_order_successfully(): void
    {
        $bangsal = Bangsal::where('nama_bangsal', 'Mawar')->first();

        $user = User::factory()->create([
            'role' => 'bangsal',
            'bangsal_id' => $bangsal->id,
        ]);

        $order = Order::factory()->create([
            'bangsal_id' => $bangsal->id,
            'created_by' => $user->id,
        ]);

        $patientOld = Patient::factory()->create([
            'bangsal_id' => $bangsal->id,
            'no_rm' => '111111',
            'nama' => 'Pasien Lama',
        ]);

        $detailOld = OrderDetail::factory()->create([
            'order_id' => $order->id,
            'patient_id' => $patientOld->id,
            'nasi' => true,
        ]);

        // Submit update with new patient data and a modified existing patient
        $response = $this->actingAs($user)
            ->put(route('bangsal.orders.update', $order), [
                'pasiens' => [
                    [
                        'nama_pasien' => 'Pasien Lama Edit',
                        'no_rm' => '111111', // Existing no_rm triggers update
                        'kamar_kelas' => 'Kamar Baru',
                        'bentuk_makanan' => ['Bubur', 'Sonde'],
                        'diet' => 'RG',
                        'keterangan' => 'Tambahan keterangan',
                    ],
                    [
                        'nama_pasien' => 'Pasien Baru',
                        'no_rm' => '222222', // New RM
                        'kamar_kelas' => 'Kamar 5B',
                        'bentuk_makanan' => ['Nasi'],
                        'diet' => 'DM',
                        'keterangan' => 'Tanpa sayur',
                    ]
                ]
            ]);

        $response->assertRedirect(route('bangsal.dashboard'));
        $response->assertSessionHas('success');

        // Assert patient old details were updated
        $patientOld->refresh();
        $this->assertEquals('Pasien Lama Edit', $patientOld->nama);
        $this->assertEquals('Kamar Baru', $patientOld->kamar);

        // Assert patient new exists
        $patientNew = Patient::where('no_rm', '222222')->first();
        $this->assertNotNull($patientNew);
        $this->assertEquals('Pasien Baru', $patientNew->nama);

        // Assert old order details deleted and replaced with new ones
        $this->assertDatabaseMissing('order_details', [
            'id' => $detailOld->id,
        ]);

        $this->assertDatabaseHas('order_details', [
            'order_id' => $order->id,
            'patient_id' => $patientOld->id,
            'nasi' => false,
            'bubur' => true,
            'makanan_cair' => false,
            'bs' => false,
            'sonde' => true,
            'diet_pasien' => 'RG',
            'keterangan' => 'Tambahan keterangan',
        ]);

        $this->assertDatabaseHas('order_details', [
            'order_id' => $order->id,
            'patient_id' => $patientNew->id,
            'nasi' => true,
            'bubur' => false,
            'makanan_cair' => false,
            'bs' => false,
            'sonde' => false,
            'diet_pasien' => 'DM',
            'keterangan' => 'Tanpa sayur',
        ]);
    }
}
