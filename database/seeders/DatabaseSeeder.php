<?php

namespace Database\Seeders;

use App\Models\BodyMeasurement;
use App\Models\Exercise;
use App\Models\ExerciseCategory;
use App\Models\Routine;
use App\Models\RoutineExercise;
use App\Models\User;
use App\Models\WorkoutSession;
use App\Models\WorkoutSet;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default User
        $user = User::updateOrCreate(
            ['email' => 'admin@fittrack.local'],
            [
                'name' => 'Aufal Wafi',
                'password' => Hash::make('password123'),
                'gender' => 'male',
                'age' => 22,
                'height_cm' => 175.0,
                'target_weight_kg' => 70.0,
                'fitness_goal' => 'bulking',
                'activity_level' => 'moderate',
                'daily_calorie_target' => 2600,
                'daily_protein_target' => 140,
                'theme_preference' => 'dark',
            ]
        );

        // 2. Categories
        $categoriesData = [
            ['name' => 'Dada (Chest)', 'slug' => 'chest', 'icon' => 'activity', 'color' => 'red', 'description' => 'Latihan otot dada mayor, minor, dan clavicular head.'],
            ['name' => 'Punggung (Back)', 'slug' => 'back', 'icon' => 'layers', 'color' => 'blue', 'description' => 'Latihan latissimus dorsi, trapezius, rhomboids, dan lower back.'],
            ['name' => 'Kaki (Legs)', 'slug' => 'legs', 'icon' => 'zap', 'color' => 'amber', 'description' => 'Latihan quadriceps, hamstrings, glutes, dan calves.'],
            ['name' => 'Bahu (Shoulders)', 'slug' => 'shoulders', 'icon' => 'shield', 'color' => 'emerald', 'description' => 'Latihan anterior, lateral, dan posterior deltoids.'],
            ['name' => 'Biceps (Lengan)', 'slug' => 'biceps', 'icon' => 'trending-up', 'color' => 'purple', 'description' => 'Latihan long head, short head, dan brachialis.'],
            ['name' => 'Triceps (Lengan)', 'slug' => 'triceps', 'icon' => 'arrow-down-right', 'color' => 'cyan', 'description' => 'Latihan lateral head, medial head, dan long head triceps.'],
            ['name' => 'Core & Perut (Abs)', 'slug' => 'core', 'icon' => 'target', 'color' => 'orange', 'description' => 'Latihan rectus abdominis, obliques, dan core stability.'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = ExerciseCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 3. Exercises Library (40+ Exercises)
        $exercisesData = [
            // Chest
            [
                'cat' => 'chest',
                'name' => 'Barbell Bench Press',
                'equipment' => 'barbell',
                'difficulty' => 'intermediate',
                'secondary' => 'Triceps, Bahu Depan',
                'instructions' => 'Berbaring di bench, genggam stang selebar bahu. Turunkan stang terkontrol ke bagian tengah dada, lalu dorong kembali ke atas.',
                'tips' => 'Jaga bahu tetap ditarik ke belakang (retraksi scapula) dan kaki menjejak kuat ke lantai.'
            ],
            [
                'cat' => 'chest',
                'name' => 'Incline Dumbbell Press',
                'equipment' => 'dumbbell',
                'difficulty' => 'beginner',
                'secondary' => 'Bahu Depan, Triceps',
                'instructions' => 'Set bench pada sudut 30-45 derajat. Dorong dumbbell ke atas hingga lengan lurus tanpa mengunci sendi siku.',
                'tips' => 'Fokus pada kontraksi dada bagian atas saat mencapai puncak gerakan.'
            ],
            [
                'cat' => 'chest',
                'name' => 'Dumbbell Chest Fly',
                'equipment' => 'dumbbell',
                'difficulty' => 'beginner',
                'secondary' => 'Bahu Depan',
                'instructions' => 'Buka lengan ke samping dengan sedikit tekukan di siku hingga terasa regangan di dada, lalu bawa kembali ke atas.',
                'tips' => 'Bayangkan memeluk pohon besar agar siku tidak ditekuk terlalu dalam.'
            ],
            [
                'cat' => 'chest',
                'name' => 'Cable Crossover',
                'equipment' => 'cable',
                'difficulty' => 'intermediate',
                'secondary' => 'Otot dada bagian dalam',
                'instructions' => 'Gunakan pulley tinggi atau menengah, tarik kedua handle ke depan hingga tangan saling bersilangan di depan dada.',
                'tips' => 'Tahan 1 detik di posisi kontraksi maksimal.'
            ],
            [
                'cat' => 'chest',
                'name' => 'Dips (Chest Focus)',
                'equipment' => 'bodyweight',
                'difficulty' => 'advanced',
                'secondary' => 'Triceps, Bahu Depan',
                'instructions' => 'Condongkan badan agak ke depan, turunkan tubuh sampai siku membentuk sudut 90 derajat, dorong kembali ke atas.',
                'tips' => 'Semakin condong tubuh ke depan, semakin banyak beban mengenai otot dada.'
            ],
            [
                'cat' => 'chest',
                'name' => 'Push Up',
                'equipment' => 'bodyweight',
                'difficulty' => 'beginner',
                'secondary' => 'Core, Triceps',
                'instructions' => 'Posisi plank tangan sejajar bahu, turunkan dada hingga mendekati lantai lalu dorong kuat ke atas.',
                'tips' => 'Jaga otot perut dan pantat tetap kencang agar pinggang tidak melengkung.'
            ],

            // Back
            [
                'cat' => 'back',
                'name' => 'Deadlift (Conventional)',
                'equipment' => 'barbell',
                'difficulty' => 'advanced',
                'secondary' => 'Hamstrings, Glutes, Lower Back, Forearms',
                'instructions' => 'Posisi kaki selebar pinggul, genggam stang, dorong lantai dengan kaki sambil meluruskan pinggul dan lutut bersamaan.',
                'tips' => 'Punggung harus lurus sempurna, jangan pernah membungkuk (cat spine).'
            ],
            [
                'cat' => 'back',
                'name' => 'Lat Pulldown',
                'equipment' => 'cable',
                'difficulty' => 'beginner',
                'secondary' => 'Biceps, Rear Delts',
                'instructions' => 'Duduk tegak, tarik bar ke arah dada bagian atas dengan menggerakkan siku ke bawah dan belakang.',
                'tips' => 'Tarik menggunakan siku, bukan sekadar menarik dengan kekuatan telapak tangan.'
            ],
            [
                'cat' => 'back',
                'name' => 'Barbell Bent-Over Row',
                'equipment' => 'barbell',
                'difficulty' => 'intermediate',
                'secondary' => 'Rhomboids, Biceps, Lower Back',
                'instructions' => 'Condongkan badan 45 derajat, punggung lurus, tarik stang barbell ke arah pusar / perut bagian bawah.',
                'tips' => 'Rapatkan bilah bahu (squeeze scapula) di puncak gerakan.'
            ],
            [
                'cat' => 'back',
                'name' => 'Seated Cable Row',
                'equipment' => 'cable',
                'difficulty' => 'beginner',
                'secondary' => 'Rhomboids, Trapezius',
                'instructions' => 'Duduk di mesin row dengan punggung tegak, tarik handle ke arah perut bawah dan tahan sebentar.',
                'tips' => 'Hindari mengayunkan badan ke belakang secara berlebihan.'
            ],
            [
                'cat' => 'back',
                'name' => 'Pull Up',
                'equipment' => 'bodyweight',
                'difficulty' => 'advanced',
                'secondary' => 'Biceps, Core',
                'instructions' => 'Genggam palang overhand, tarik badan ke atas hingga dagu melewati palang besi.',
                'tips' => 'Jika belum kuat, gunakan resistance band atau mesin assisted pull-up.'
            ],
            [
                'cat' => 'back',
                'name' => 'Single-Arm Dumbbell Row',
                'equipment' => 'dumbbell',
                'difficulty' => 'beginner',
                'secondary' => 'Lats, Rhomboids',
                'instructions' => 'Satu lutut dan tangan di atas bench, tarik dumbbell dengan tangan lainnya ke arah pinggul.',
                'tips' => 'Jaga jalur gerakan melengkung ke arah pinggul agar lats terisolasi optimal.'
            ],

            // Legs
            [
                'cat' => 'legs',
                'name' => 'Barbell Back Squat',
                'equipment' => 'barbell',
                'difficulty' => 'intermediate',
                'secondary' => 'Glutes, Hamstrings, Core',
                'instructions' => 'Letakkan stang di punggung atas/trapezius, turunkan pinggul seperti hendak duduk hingga paha sejajar lantai, lalu dorong kembali naik.',
                'tips' => 'Lutut mengikuti arah jari kaki dan dada tetap membusung tegak.'
            ],
            [
                'cat' => 'legs',
                'name' => 'Romanian Deadlift (RDL)',
                'equipment' => 'barbell',
                'difficulty' => 'intermediate',
                'secondary' => 'Hamstrings, Glutes, Lower Back',
                'instructions' => 'Kaki selebar pinggul, dorong pantat ke belakang dengan lutut sedikit ditekuk hingga terasa regangan kuat di paha belakang.',
                'tips' => 'Gerakan ini adalah hip hinge, bukan jongkok (squat).'
            ],
            [
                'cat' => 'legs',
                'name' => 'Leg Press',
                'equipment' => 'machine',
                'difficulty' => 'beginner',
                'secondary' => 'Quads, Glutes',
                'instructions' => 'Letakkan kaki di platform selebar bahu, turunkan beban hingga lutut 90 derajat, dorong kembali tanpa mengunci lutut.',
                'tips' => 'Jangan pernah mengunci lutut (lockout) di posisi teratas demi keamanan sendi.'
            ],
            [
                'cat' => 'legs',
                'name' => 'Leg Extension',
                'equipment' => 'machine',
                'difficulty' => 'beginner',
                'secondary' => 'Quadriceps',
                'instructions' => 'Duduk tegak, luruskan kaki ke atas untuk mengangkat bantalan beban dan tahan 1 detik di puncak.',
                'tips' => 'Sangat bagus untuk isolasi dan pemanasan otot paha depan.'
            ],
            [
                'cat' => 'legs',
                'name' => 'Lying Leg Curl',
                'equipment' => 'machine',
                'difficulty' => 'beginner',
                'secondary' => 'Hamstrings',
                'instructions' => 'Tengkurap di mesin, tekuk lutut untuk menarik bantalan ke arah bokong.',
                'tips' => 'Jaga pinggul tetap menempel di bantalan mesin selama menarik beban.'
            ],
            [
                'cat' => 'legs',
                'name' => 'Bulgarian Split Squat',
                'equipment' => 'dumbbell',
                'difficulty' => 'intermediate',
                'secondary' => 'Quads, Glutes, Keseimbangan',
                'instructions' => 'Satu kaki di belakang bertumpu pada bench, turunkan tubuh vertikal hingga lutut depan membentuk 90 derajat.',
                'tips' => 'Latihan unilateral terbaik untuk memperbaiki ketidakseimbangan kekuatan kaki.'
            ],
            [
                'cat' => 'legs',
                'name' => 'Standing Calf Raise',
                'equipment' => 'machine',
                'difficulty' => 'beginner',
                'secondary' => 'Gastrocnemius (Betis)',
                'instructions' => 'Berjinjit setinggi mungkin dengan beban di bahu, turunkan tumit hingga betis meregang penuh.',
                'tips' => 'Lakukan gerakan lambat dan tahan di posisi jinjit selama 2 detik.'
            ],

            // Shoulders
            [
                'cat' => 'shoulders',
                'name' => 'Overhead Press (OHP)',
                'equipment' => 'barbell',
                'difficulty' => 'intermediate',
                'secondary' => 'Triceps, Core, Upper Chest',
                'instructions' => 'Berdiri tegak, dorong stang dari setinggi tulang selangka lurus ke atas kepala.',
                'tips' => 'Kencangkan pantat dan perut agar punggung bawah tidak melengkung berlebihan.'
            ],
            [
                'cat' => 'shoulders',
                'name' => 'Dumbbell Lateral Raise',
                'equipment' => 'dumbbell',
                'difficulty' => 'beginner',
                'secondary' => 'Bahu Samping (Lateral Deltoid)',
                'instructions' => 'Angkat dumbbell ke samping hingga setinggi bahu dengan siku sedikit ditekuk.',
                'tips' => 'Pimpin gerakan dengan siku, jangan menggunakan ayunan badan.'
            ],
            [
                'cat' => 'shoulders',
                'name' => 'Seated Dumbbell Shoulder Press',
                'equipment' => 'dumbbell',
                'difficulty' => 'beginner',
                'secondary' => 'Bahu Depan, Triceps',
                'instructions' => 'Duduk dengan sandaran tegak 85-90 derajat, dorong dumbbell ke atas hingga hampir bersentuhan.',
                'tips' => 'Pertahankan pergelangan tangan tetap lurus di atas siku.'
            ],
            [
                'cat' => 'shoulders',
                'name' => 'Face Pull',
                'equipment' => 'cable',
                'difficulty' => 'beginner',
                'secondary' => 'Rear Delts, Rotator Cuff, Upper Traps',
                'instructions' => 'Gunakan tali rope di kabel tinggi, tarik ke arah dahi sambil memutar tangan ke luar.',
                'tips' => 'Sangat penting untuk kesehatan postur dan rotasi bahu.'
            ],
            [
                'cat' => 'shoulders',
                'name' => 'Reverse Pec Deck / Rear Delt Fly',
                'equipment' => 'machine',
                'difficulty' => 'beginner',
                'secondary' => 'Bahu Belakang (Posterior Delts)',
                'instructions' => 'Duduk menghadap mesin pec deck, rentangkan tangan ke belakang menggunakan otot bahu belakang.',
                'tips' => 'Fokus pada bahu belakang, bukan merapatkan punggung tengah.'
            ],

            // Biceps
            [
                'cat' => 'biceps',
                'name' => 'Barbell Bicep Curl',
                'equipment' => 'barbell',
                'difficulty' => 'beginner',
                'secondary' => 'Forearms',
                'instructions' => 'Berdiri tegak, tekuk siku untuk mengangkat stang ke arah dada tanpa menggerakkan siku ke depan.',
                'tips' => 'Kunci posisi siku di samping pinggang sepanjang gerakan.'
            ],
            [
                'cat' => 'biceps',
                'name' => 'Incline Dumbbell Curl',
                'equipment' => 'dumbbell',
                'difficulty' => 'intermediate',
                'secondary' => 'Long Head Bicep',
                'instructions' => 'Duduk di bench kemiringan 45-60 derajat, angkat dumbbell dengan posisi lengan tergantung di belakang badan.',
                'tips' => 'Memberikan regangan (stretch) maksimal pada kepala panjang bicep.'
            ],
            [
                'cat' => 'biceps',
                'name' => 'Hammer Curl',
                'equipment' => 'dumbbell',
                'difficulty' => 'beginner',
                'secondary' => 'Brachialis, Forearm',
                'instructions' => 'Genggam dumbbell dengan telapak tangan saling berhadapan (netral), angkat ke atas.',
                'tips' => 'Menebalkan ukuran lengan dan memperkuat pegangan pergelangan tangan.'
            ],
            [
                'cat' => 'biceps',
                'name' => 'Preacher Curl',
                'equipment' => 'machine',
                'difficulty' => 'beginner',
                'secondary' => 'Short Head Bicep',
                'instructions' => 'Sandarkan lengan di bantalan preacher, lakukan curl terkontrol tanpa mengangkat siku.',
                'tips' => 'Mencegah segala bentuk momentum ayunan tubuh.'
            ],

            // Triceps
            [
                'cat' => 'triceps',
                'name' => 'Tricep Rope Pushdown',
                'equipment' => 'cable',
                'difficulty' => 'beginner',
                'secondary' => 'Lateral Head Triceps',
                'instructions' => 'Genggam tali di mesin kabel, dorong ke bawah dan rentangkan tali ke samping di posisi bawah.',
                'tips' => 'Kunci siku agar tidak maju mundur.'
            ],
            [
                'cat' => 'triceps',
                'name' => 'Skull Crushers (Lying Triceps Ext)',
                'equipment' => 'barbell',
                'difficulty' => 'intermediate',
                'secondary' => 'Medial & Long Head Triceps',
                'instructions' => 'Berbaring di bench, pegang EZ-bar, tekuk siku hingga stang turun ke dahi/ubun-ubun, lalu dorong naik.',
                'tips' => 'Jaga lengan atas tetap vertikal tegak lurus lantai.'
            ],
            [
                'cat' => 'triceps',
                'name' => 'Overhead Cable Tricep Extension',
                'equipment' => 'cable',
                'difficulty' => 'beginner',
                'secondary' => 'Long Head Triceps',
                'instructions' => 'Posisikan kabel di belakang kepala, dorong tali ke depan atas melampaui kepala.',
                'tips' => 'Memberikan stimulasi terbaik untuk kepala panjang otot triceps.'
            ],

            // Core
            [
                'cat' => 'core',
                'name' => 'Hanging Leg Raise',
                'equipment' => 'bodyweight',
                'difficulty' => 'intermediate',
                'secondary' => 'Hip Flexors, Grip Strength',
                'instructions' => 'Bergelantungan di palang, angkat kedua kaki lurus atau ditekuk ke arah dada dengan mengontraksikan perut.',
                'tips' => 'Gulung pinggul ke atas, jangan hanya mengayunkan kaki.'
            ],
            [
                'cat' => 'core',
                'name' => 'Plank',
                'equipment' => 'bodyweight',
                'difficulty' => 'beginner',
                'secondary' => 'Core Stability, Bahu',
                'instructions' => 'Tahan tubuh lurus horizontal dengan bertumpu pada lengan bawah dan jari kaki.',
                'tips' => 'Kunci otot perut sedalam mungkin seolah siap menerima pukulan.'
            ],
            [
                'cat' => 'core',
                'name' => 'Cable Crunch',
                'equipment' => 'cable',
                'difficulty' => 'intermediate',
                'secondary' => 'Rectus Abdominis',
                'instructions' => 'Berlutut di depan katrol kabel, genggam rope di dekat kepala dan gulung tubuh ke bawah mengencangkan perut.',
                'tips' => 'Gerakan berasal dari lekukan tulang belakang, bukan pinggul.'
            ],
        ];

        $exercises = [];
        foreach ($exercisesData as $item) {
            $cat = $categories[$item['cat']];
            $slug = Str::slug($item['name']);
            $exercises[$slug] = Exercise::updateOrCreate(
                ['slug' => $slug],
                [
                    'exercise_category_id' => $cat->id,
                    'name' => $item['name'],
                    'slug' => $slug,
                    'equipment' => $item['equipment'],
                    'difficulty' => $item['difficulty'],
                    'secondary_muscles' => $item['secondary'],
                    'instructions' => $item['instructions'],
                    'tips' => $item['tips'],
                    'is_custom' => false,
                ]
            );
        }

        // 4. Default Routine Templates
        // Template 1: Push Day
        $pushRoutine = Routine::updateOrCreate(
            ['name' => 'Push Day (Dada, Bahu, Triceps)'],
            [
                'user_id' => null, // system template
                'target_split' => 'PPL',
                'day_schedule' => 'Senin / Kamis',
                'description' => 'Fokus melatih otot dorong: Dada, Bahu Depan/Samping, dan Triceps.',
                'is_system_template' => true,
            ]
        );

        $pushList = [
            ['slug' => 'barbell-bench-press', 'order' => 1, 'sets' => 4, 'reps' => '6-8', 'rest' => 120, 'notes' => 'Compound berat, pemanasan 2 set'],
            ['slug' => 'incline-dumbbell-press', 'order' => 2, 'sets' => 3, 'reps' => '8-10', 'rest' => 90, 'notes' => 'Fokus dada atas'],
            ['slug' => 'seated-dumbbell-shoulder-press', 'order' => 3, 'sets' => 3, 'reps' => '8-12', 'rest' => 90, 'notes' => 'Jaga postur tegak'],
            ['slug' => 'dumbbell-lateral-raise', 'order' => 4, 'sets' => 4, 'reps' => '12-15', 'rest' => 60, 'notes' => 'Bahu samping, repetisi tinggi'],
            ['slug' => 'tricep-rope-pushdown', 'order' => 5, 'sets' => 3, 'reps' => '10-12', 'rest' => 60, 'notes' => 'Squeeze tricep di bawah'],
        ];

        foreach ($pushList as $item) {
            if (isset($exercises[$item['slug']])) {
                RoutineExercise::updateOrCreate(
                    ['routine_id' => $pushRoutine->id, 'exercise_id' => $exercises[$item['slug']]->id],
                    [
                        'order_index' => $item['order'],
                        'target_sets' => $item['sets'],
                        'target_reps' => $item['reps'],
                        'rest_seconds' => $item['rest'],
                        'notes' => $item['notes'],
                    ]
                );
            }
        }

        // Template 2: Pull Day
        $pullRoutine = Routine::updateOrCreate(
            ['name' => 'Pull Day (Punggung & Biceps)'],
            [
                'user_id' => null,
                'target_split' => 'PPL',
                'day_schedule' => 'Selasa / Jumat',
                'description' => 'Fokus melatih otot tarik: Seluruh punggung, Bahu Belakang, dan Biceps.',
                'is_system_template' => true,
            ]
        );

        $pullList = [
            ['slug' => 'lat-pulldown', 'order' => 1, 'sets' => 4, 'reps' => '8-10', 'rest' => 90, 'notes' => 'Tarik pakai siku'],
            ['slug' => 'barbell-bent-over-row', 'order' => 2, 'sets' => 3, 'reps' => '8-10', 'rest' => 90, 'notes' => 'Punggung datar'],
            ['slug' => 'face-pull', 'order' => 3, 'sets' => 3, 'reps' => '12-15', 'rest' => 60, 'notes' => 'Rotasi bahu luar'],
            ['slug' => 'barbell-bicep-curl', 'order' => 4, 'sets' => 3, 'reps' => '8-10', 'rest' => 75, 'notes' => 'Tanpa ayunan'],
            ['slug' => 'hammer-curl', 'order' => 5, 'sets' => 3, 'reps' => '10-12', 'rest' => 60, 'notes' => 'Bentuk ketebalan lengan'],
        ];

        foreach ($pullList as $item) {
            if (isset($exercises[$item['slug']])) {
                RoutineExercise::updateOrCreate(
                    ['routine_id' => $pullRoutine->id, 'exercise_id' => $exercises[$item['slug']]->id],
                    [
                        'order_index' => $item['order'],
                        'target_sets' => $item['sets'],
                        'target_reps' => $item['reps'],
                        'rest_seconds' => $item['rest'],
                        'notes' => $item['notes'],
                    ]
                );
            }
        }

        // Template 3: Leg Day
        $legRoutine = Routine::updateOrCreate(
            ['name' => 'Leg Day (Paha & Betis)'],
            [
                'user_id' => null,
                'target_split' => 'PPL',
                'day_schedule' => 'Rabu / Sabtu',
                'description' => 'Fokus melatih otot bawah: Quads, Hamstrings, Glutes, dan Calves.',
                'is_system_template' => true,
            ]
        );

        $legList = [
            ['slug' => 'barbell-back-squat', 'order' => 1, 'sets' => 4, 'reps' => '6-8', 'rest' => 120, 'notes' => 'Kedalaman minimal paha sejajar lantai'],
            ['slug' => 'romanian-deadlift-rdl', 'order' => 2, 'sets' => 3, 'reps' => '8-10', 'rest' => 90, 'notes' => 'Regangan paha belakang'],
            ['slug' => 'leg-press', 'order' => 3, 'sets' => 3, 'reps' => '10-12', 'rest' => 90, 'notes' => 'Jangan kunci lutut'],
            ['slug' => 'standing-calf-raise', 'order' => 4, 'sets' => 4, 'reps' => '12-15', 'rest' => 60, 'notes' => 'Tahan 2 detik di puncak'],
            ['slug' => 'hanging-leg-raise', 'order' => 5, 'sets' => 3, 'reps' => '12-15', 'rest' => 60, 'notes' => 'Kunci perut bawah'],
        ];

        foreach ($legList as $item) {
            if (isset($exercises[$item['slug']])) {
                RoutineExercise::updateOrCreate(
                    ['routine_id' => $legRoutine->id, 'exercise_id' => $exercises[$item['slug']]->id],
                    [
                        'order_index' => $item['order'],
                        'target_sets' => $item['sets'],
                        'target_reps' => $item['reps'],
                        'rest_seconds' => $item['rest'],
                        'notes' => $item['notes'],
                    ]
                );
            }
        }
    }
}
