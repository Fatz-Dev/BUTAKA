# Outline Presentasi: Perancangan & Pengujian Backend Sistem BUTAKA

Presentasi ini merangkum proses pengembangan backend menggunakan framework Laravel dan metodologi pengujian API untuk memastikan integritas sistem.

---

## BAGIAN 1: PROSES PENGEMBANGAN BACKEND (4 SLIDE)

### Slide 1: Inisialisasi & Gambaran Umum Backend
*   **Poin Utama PPT**:
    Backend BUTAKA adalah inti dari sistem Buku Tamu Karyawan berbasis Laravel 12 yang dirancang untuk mengelola data kunjungan dan feedback secara terpusat. Tahap awal pengembangan difokuskan pada pembangunan infrastruktur inti, termasuk instalasi framework dan konfigurasi keamanan Laravel Sanctum menggunakan sistem token untuk melindungi akses API secara menyeluruh.
*   **Penjelasan Lengkap (Speaker Notes)**:
    Backend BUTAKA merupakan inti dari sistem Buku Tamu Karyawan yang dirancang untuk mengelola repositori data kunjungan dan feedback secara terpusat. Dikembangkan menggunakan framework Laravel 12, sistem ini bertugas memproses logika bisnis di balik layar, mulai dari autentikasi keamanan hingga manajemen data tamu yang dinamis.

    Proses inisialisasi melibatkan pemasangan sistem pengaman berbasis token (Sanctum) untuk memastikan bahwa hanya pengguna yang sah yang dapat mengakses data. Backend ini memastikan bahwa seluruh informasi yang diperlukan oleh aplikasi web maupun mobile dapat diakses dengan cepat, akurat, dan aman melalui antarmuka API yang terstandarisasi.
*   **Saran Visual**: Logo Laravel 12, Logo PHP 8.x, dan diagram blok sederhana posisi Backend terhadap ekosistem sistem.

### Slide 2: Pemodelan Data & Struktur Arsitektur (MVC)
*   **Poin Utama PPT**:
    Sistem ini mengadopsi struktur arsitektur Model-View-Controller (MVC) untuk menciptakan kode yang rapi, terorganisir, dan mudah dikembangkan. Pemodelan data dilakukan dengan merancang skema tabel MySQL untuk Users, Visitors, dan Feedback, serta menggunakan Seeder untuk menyiapkan data administratif awal agar sistem siap digunakan untuk pengujian login.
*   **Penjelasan Lengkap (Speaker Notes)**:
    Struktur backend BUTAKA mengadopsi pola arsitektur Model-View-Controller (MVC). Komponen Model bertanggung jawab penuh atas interaksi dan definisi skema data di database, sementara Controller bertindak sebagai jembatan yang mengatur alur logika dan instruksi aplikasi. Meskipun dalam konteks API lapisan View digantikan oleh format JSON, pemisahan tanggung jawab yang jelas ini memudahkan pemeliharaan sistem.

    Kami juga merancang denah tabel database secara spesifik untuk kebutuhan buku tamu dan menyediakan data default menggunakan Seeder. Hal ini memungkinkan sistem memiliki akun admin pertama secara otomatis untuk operasional awal dan pengujian otentikasi.
*   **Saran Visual**: Diagram Arsitektur MVC, Screenshot ERD (Entity Relationship Diagram) atau struktur tabel database.

### Slide 3: Logika Bisnis & Fungsi Utama Sistem
*   **Poin Utama PPT**:
    Fungsi utama backend BUTAKA adalah mengelola siklus hidup data tamu dan otorisasi pengguna secara otomatis melalui logika bisnis di Controller. Sistem menerapkan validasi input yang ketat pada setiap Request untuk menjaga integritas data, mulai dari proses registrasi kunjungan hingga penyajian data analitik dalam bentuk ringkasan laporan yang akurat.
*   **Penjelasan Lengkap (Speaker Notes)**:
    Fungsi utama dari backend BUTAKA adalah sebagai pusat kendali operasional yang menangani manajemen siklus hidup data tamu dan otorisasi pengguna. Fitur inti mencakup sistem registrasi kunjungan yang dilengkapi validasi input ketat untuk memastikan tidak ada informasi yang kosong atau salah format.

    Kami membangun logika bisnis di dalam Controller untuk memastikan instruksi kerja utama, seperti menyimpan data tamu dan menampilkan laporan grafik, berjalan sesuai skenario. Implementasi Laravel Sanctum juga berfungsi melindungi data sensitif pengunjung dari akses yang tidak sah melalui mekanisme keamanan yang berlapis.
*   **Saran Visual**: Cuplikan kode Controller, Flowchart alur pengolahan data dari input ke database.

### Slide 4: Konfigurasi Gateway & Alur Kerja API
*   **Poin Utama PPT**:
    Alur kerja API dimulai dari registrasi endpoint melalui sistem Routing yang menghubungkan aplikasi frontend dengan logika backend. Setiap permintaan akan melewati lapisan keamanan Middleware untuk verifikasi token sebelum diproses oleh server, yang kemudian mengirimkan jawaban dalam format JSON yang konsisten melalui standardisasi ApiResponse untuk profesionalitas komunikasi data.
*   **Penjelasan Lengkap (Speaker Notes)**:
    Alur kerja backend dimulai saat sistem menerima permintaan (Request) dari sisi pengguna melalui endpoint API tertentu. Permintaan tersebut akan melewati lapisan keamanan Middleware untuk verifikasi hak akses (Admin vs Resepsionis) secara otomatis sebelum diteruskan ke Controller.

    Setelah logika bisnis dieksekusi menggunakan Eloquent ORM, server akan memberikan jawaban (Response) akhir. Kami menyeragamkan format jawaban ini menggunakan ApiResponse (Success, Message, Data) agar komunikasi antara backend dan frontend (Web/Mobile) menjadi lebih konsisten, mudah dipahami, dan profesional.
*   **Saran Visual**: Tabel Daftar Endpoint API, Contoh cuplikan respons JSON yang konsisten.

---

## BAGIAN 2: METODOLOGI & HASIL PENGUJIAN API (4 SLIDE)

### Slide 5: Arsitektur & Strategi Pengujian API
*   **Poin Utama PPT**:
    Strategi pengujian backend BUTAKA menggabungkan metode manual menggunakan Postman dan otomatis melalui PHPUnit untuk menjamin reliabilitas setiap endpoint. Fokus utama pengujian ini adalah mematikan setiap respons server mematuhi standar REST API dengan verifikasi status code yang akurat, guna memastikan stabilitas sistem dalam menangani berbagai kondisi beban dan input.
*   **Penjelasan Lengkap (Speaker Notes)**:
    Ekosistem pengujian backend BUTAKA dirancang melalui kombinasi metode manual dan otomatis untuk menjamin reliabilitas setiap endpoint. Pengujian manual dilakukan menggunakan Postman/Insomnia untuk mensimulasikan berbagai skenario HTTP Request secara langsung, mulai dari kondisi sukses hingga kegagalan.

    Di sisi lain, PHPUnit digunakan sebagai mesin pengujian otomatis pada level Unit dan integrasi kode untuk memastikan tidak ada fungsi yang rusak setelah perubahan. Strategi ini sangat penting untuk menjamin setiap respons server membawa status code yang tepat (seperti 200 OK atau 422 Validation Error) sesuai dengan standar industri RESTful API.
*   **Saran Visual**: Logo Postman & PHPUnit, Screenshot dashboard pengujian/terminal green test.

### Slide 6: Verifikasi Fungsional: Modul Auth & Visitor
*   **Poin Utama PPT**:
    Verifikasi fungsional dilakukan untuk memastikan alur data berjalan lancar mulai dari otentikasi admin hingga pendaftaran kunjungan tamu. Skenario pengujian mencakup validasi payload data yang dikirim melalui Request POST, di mana hasil pengujian membuktikan bahwa integrasi antara endpoint dan basis data berfungsi akurat tanpa adanya risiko degradasi atau duplikasi informasi.
*   **Penjelasan Lengkap (Speaker Notes)**:
    Pengujian pada modul autentikasi dan pendaftaran tamu dilakukan untuk memastikan alur data berjalan mulus dari pintu masuk hingga ke penyimpanan database. Kami menguji fungsionalitas utama seperti login administratif dan penambahan data pengunjung baru dengan parameter yang ditentukan.

    Hasil pengujian menunjukkan bahwa sistem mampu memproses data dengan cepat dan tepat. Ketika Request dikirim, server memberikan respons 201 Created dengan detail ID kunjungan yang akurat. Hal ini membuktikan bahwa mekanisme penyimpanan data telah terintegrasi sempurna dengan logika bisnis yang telah dibangun sebelumnya.
*   **Saran Visual**: Screenshot Request POST di Postman (Payload), Tabel database yang terisi data uji.

### Slide 7: Audit Keamanan & Validasi Otorisasi Token
*   **Poin Utama PPT**:
    Aspek keamanan diaudit secara ketat melalui pengujian mekanisme otorisasi berbasis token Laravel Sanctum dan Middleware pelindung. Sistem diuji untuk menolak akses ilegal tanpa token yang valid serta memverifikasi pembatasan hak akses antar peran, sehingga integritas data sensitif tetap terjaga dan hanya dapat diakses oleh personil yang memiliki izin administratif yang sah.
*   **Penjelasan Lengkap (Speaker Notes)**:
    Aspek keamanan menjadi prioritas utama melalui audit ketat terhadap mekanisme otorisasi berbasis token Laravel Sanctum. Kami melakukan pengujian "Unauthorized Access" untuk memastikan sistem benar-benar menolak permintaan yang tidak menyertakan Bearer Token yang valid.

    Selain itu, kami melakukan pengecekan hak akses antar peran (RBAC). Misalnya, kami memastikan peran "Receptionist" benar-benar dilarang mengakses fitur administratif yang khusus untuk "Admin". Implementasi Middleware ini terbukti sukses menciptakan lapisan perlindungan data yang solid, mencegah eksploitasi akses yang tidak diinginkan pada modul-modul sensitif.
*   **Saran Visual**: Screenshot Respons 401 Unauthorized, Diagram hirarki hak akses (Roles).

### Slide 8: Analisis Hasil & Status Kesiapan Sistem
*   **Poin Utama PPT**:
    Analisis akhir menunjukkan bahwa backend BUTAKA telah mencapai status stabil dengan latency respons rata-rata di bawah 500ms dan reliabilitas tinggi dalam menangani beban kerja. Seluruh komponen mulai dari struktur folder hingga format respons API dinyatakan siap sepenuhnya untuk diintegrasikan dengan platform frontend, menjadikannya solusi manajemen buku tamu yang andal dan profesional.
*   **Penjelasan Lengkap (Speaker Notes)**:
    Tahap akhir pengujian memberikan gambaran komprehensif mengenai performa dan stabilitas backend BUTAKA secara keseluruhan. Kami mengevaluasi metrik kunci seperti Latency, di mana rata-rata waktu respons server tetap berada di bawah 500ms untuk memastikan pengalaman pengguna yang lancar.

    Sistem juga terbukti sangat handal dalam menangani validasi input yang kompleks dan memberikan pesan kesalahan yang deskriptif. Kesimpulannya, seluruh infrastruktur backend telah dinyatakan stabil dan siap dikonsumsi oleh platform Frontend (Web/Mobile). Ini menandakan kesiapan penuh sistem untuk masuk ke fase operasional final.
*   **Saran Visual**: Grafik Latency/Response Time, Badge "Stable Version 1.0", Icon "Ready for Integration".
