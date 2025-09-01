<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\QueueCommandModel;
use App\Models\SensorStatusModel;
use App\Libraries\PartikelJob;

class WorkerQueue extends BaseCommand
{
    protected $group = 'Custom';
    protected $name = 'worker:queue';
    protected $description = 'Queue worker for sensor commands';

    public function run(array $params)
    {
        $queueModel = new QueueCommandModel();
        $statusModel = new SensorStatusModel();
        $jobService = new PartikelJob();

        CLI::write("Worker started...");

        while (true) {
            $job = $queueModel->where('processed', 0)->orderBy('id', 'ASC')->first();

            if ($job) {
                CLI::write("Processing: " . $job['command']);

                switch ($job['command']) {
                    case 'start':
                        $jobService->start();
                        $statusModel->update(1, [
                            'status' => 'running',
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                        break;

                    case 'stop':
                        $jobService->stop();
                        $statusModel->update(1, [
                            'status' => 'stopped',
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                        break;

                    default:
                        CLI::write("Unknown command: " . $job['command']);
                        break;
                }

                $queueModel->update($job['id'], [
                    'processed' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            sleep(2);
        }
    }
}
