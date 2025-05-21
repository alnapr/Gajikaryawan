<?php
//mendapatkan data dari file gaji
function loadData()
{
    $file = __DIR__ . '/model/gaji.php';
    if (file_exists($file)) { //pengecekan data
        return include ($file); //jika data ada akan masuk
    }
    return[]; //jika data kosong dikembalikan 
}

//menyimpan data ke file gaji
function saveData($DataKaryawan)
{
    $file = __DIR__ . '/model/gaji.php';
    $content = "<?php\nreturn " . var_export($DataKaryawan, true) . ";\n"; //memasukkan konten/isi menggunakan php
    file_put_contents($file, $content);
}

//menampilkan menu
function DisplayMenu()
{
    echo "\n== PENGELOLAAN GAJI KARYAWAN ==\n";
    echo "1. Lihat Karyawan\n";
    echo "2. Tambah Karyawan\n";
    echo "3. Update Karyawan\n";
    echo "4. Hapus Karyawan\n";
    echo "5. Hitung Gaji Karyawan\n";
    echo "6. Keluar Aplikasi\n";
    echo "Pilih menu (1-6): ";
}

//mendapatkan input
function getCleanInput()
{
    return trim(fgets(STDIN));
}

//PROSES UTAMA
$running = true; //ketika running true program terus berjalan
$DataKaryawan = loadData();

while($running) {
    DisplayMenu();
    $Pilihan = getCleanInput(); //memilih tempat menginput

    switch ($Pilihan){
        case "1":
            //Lihat Karyawan
            echo "\nDaftar Karyawan:\n";
            if (empty($DataKaryawan)) {
                echo "DATA KOSONG.\n";
            } else{
                foreach ($DataKaryawan as $id => $karyawan) {
                    echo ($id + 1) . ". Nama: {$karyawan['Nama']}, Jabatan: {$karyawan['Jabatan']}\n";
                }
            }
            echo "\nTekan ENTER untuk kembali. . .";
            fgets(STDIN);
            break;

        case "2":
            //Tambah Karyawan
            echo "\nTambahkan nama Karyawan: ";
            $nama = getCleanInput();
            $jabatanValid = false;
            while (!$jabatanValid){

            echo "Masukkan jabatan (sekertaris, manager, divisi): ";
            $jabatan = getCleanInput();

            if (in_array($jabatan, ['sekertaris', 'manager', 'divisi'])) {
                $jabatanValid = true;
            } else {
                echo "Input jabatan tidak valid. Harap masukkan salah satu dari (sekertaris, manager, divisi)\n";
            }
                
            }

            $DataKaryawan[] = ['Nama' => $nama, 'Jabatan' => $jabatan];
            saveData($DataKaryawan);
            echo "Data berhasil ditambahkan!!!\n";

            echo "\nTekan ENTER untuk kembali. . .";
            fgets(STDIN);
            break;

        case "3":
            //Update Data Karyawan
            echo "\nUpdate Data Karyawan\n";
            if (empty($DataKaryawan)) {
                echo "DATA KOSONG.\n";
            } else{
                foreach ($DataKaryawan as $id => $karyawan) {
                    echo ($id + 1) . ". Nama: {$karyawan['Nama']}, Jabatan: {$karyawan['Jabatan']}\n";
                }
                echo "\nMasukkan id karyawan: \n";
                $id = (int)getCleanInput() - 1;

                if(isset($DataKaryawan[$id])){
                    echo "Masukkan data nama baru (biarkan kosong jika tidak diubah) :" ;
                    $nama = getCleanInput();
                    echo "Masukkan data jabatan baru (biarkan kosong jika tidak diubah) :" ;
                    $jabatan = getCleanInput();

                    if (!empty($nama)) {
                        $DataKaryawan[$id]['Nama'] = $nama ;
                    }
                    if (!empty($jabatan)) {
                        $DataKaryawan[$id]['Jabatan'] = $jabatan ;
                    }

                    saveData($DataKaryawan);
                    echo "Data berhasil di update!\n";
                } else{
                    echo "Data tidak ditemukan!\n";
                }
            }
            echo "\nTekan ENTER untuk kembali. . .";
            fgets(STDIN);
            break;
        
        case "4":
            //Hapus Data Karyawan
            echo "\nHapus Data Karyawan\n";
            if (empty($DataKaryawan)) {
                echo "Data karyawan tidak terdaftar.\n";
            } else{
                foreach ($DataKaryawan as $id => $karyawan) {
                    echo ($id + 1) . ". Nama: {$karyawan['Nama']}, Jabatan: {$karyawan['Jabatan']}\n";
                }
            }                
            echo "\nMasukkan id karyawan: \n";
                $id = (int)getCleanInput() - 1;

                if(isset($DataKaryawan[$id])){
                    echo "Data karyawan yang akan dihapus:\n";
                    echo "Nama      : {$DataKaryawan[$id]['Nama']}\n";
                    echo "Jabatan   : {$DataKaryawan[$id]['Jabatan']}\n";

                    echo "\nApakah anda yain ingin menghapus data ini? (y/n): ";
                    $konfirmasi = strtolower(trim(fgetc(STDIN)));
                    
                    if ($konfirmasi === 'y') {
                        unset ($DataKaryawan[$id]);
                        $DataKaryawan = array_values($DataKaryawan);
                        saveData ($DataKaryawan);
                        echo "Data berhasil dihapus!\n";
                    } else {
                        echo "Data batal dihapus.\n";
                    }
                } else {
                    echo "Data tidak ditemukan!\n";
                }
            }
            echo "\nTekan ENTER untuk kembali. . .";
            fgets(STDIN);
            break;

        case "5":
            //Hitung gaji ALGORITMA
            function hitunggaji($jabatan, $jam_lembur, $rating) {
                //logika atau perhitungan tiap variabelnya
                if ($jabatan == "Sekertaris") {
                    $gajipokok = 4500000;
                    $tunjangan = 2000000;
                } else if ($jabatan == "Manager") {
                    $gajipokok = 3200000;
                    $tunjangan = 1500000;
                } else {
                    $gajipokok = 2190000;
                    $tunjangan = 800000;
                }
                $upahlembur = $jam_lembur * 35000;
                $bonus_kinerja = match ($rating) {
                    5 => 0.2 * $gajipokok,
                    4 => 0.15 * $gajipokok,
                    3 => 0.1 * $gajipokok,
                    2 => 0.05 * $gajipokok,
                    default => 0
                };

                return [
                    'gajipokok' => $gajipokok,
                    'tunjangan' => $tunjangan,
                    'upahlembur' => $upahlembur,
                    'bonuskinerja' => $bonus_kinerja,
                    'totalgaji' => $gajipokok + $tunjangan + $upahlembur + $bonus_kinerja
                ];
            }
            echo "\nHitung Gaji Karyawan\n";
            if (empty($DataKaryawan)) {
                echo "Tidak ada data.\n";
            } else {
                foreach ($DataKaryawan as $id => $karyawan) {
                    echo ($id + 1) . ". {$karyawan['Nama']} - {$karyawan['Jabatan']}\n";
                }
                echo "Masukkan ID data.\n";
                $id = (int)getCleanInput() -1;

                if(isset($DataKaryawan[$id])){
                    echo "Masukkan jumlah jam lembur: ";
                    $jam_lembur = (int)trim(fgets(STDIN));
                    echo "Masukkan rating kerja (1-5): ";
                    $rating = (int)trim(fgets(STDIN));

                    $gaji = hitunggaji($DataKaryawan[$id]['Jabatan'], $jam_lembur, $rating);
                    
                    $DataKaryawan[$id]['last_gaji'] = array_merge(
                        $gaji,
                        [
                            'jamlembur' => $jam_lembur,
                            'rating' => $rating,
                            'tanggal' => date('Y-m-d H:i:s')
                        ]
                    );
                    saveData($DataKaryawan);

                    echo "\nRincian Gaji {$DataKaryawan[$id]['Nama']}:\n";
                    echo "Gaji Pokok: Rp " . number_format($gaji['gajipokok']) . "\n";
                    echo "Tunjangan: Rp " . number_format($gaji['tunjangan']) . "\n";
                    echo "Upah Lembur: Rp " . number_format($gaji['upahlembur']) . "\n";
                    echo "Bonus Kerja: Rp " . number_format($gaji['bonuskinerja']) . "\n";
                    echo "Total Gaji: Rp " . number_format($gaji['totalgaji']) . "\n";
                } else {
                    echo " Data tidak ditemukan !!!\n";
                }
            
            echo "\nTekan ENTER untuk kembali. . .";
            fgets(STDIN);
            break;

        case "6":
            echo "\n Terimakasih! Semoga Bermanfaat!\n";
            $running = false;
            break;

        default:
            echo "Menu tidak valid !\n";
        
    }
}
?>