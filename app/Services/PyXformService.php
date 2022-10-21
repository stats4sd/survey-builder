<?php


namespace App\Services;

use App\Models\Xlsform;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class PyXformService
{

    public function testXlsform(Xlsform $xlsform)
    {
        $process = new Process([config('services.odk_central.pyxform-path'), Storage::path($xlsform->xlsfile)]);

        $process->run();
        if (!$process->isSuccessful()) {

            Log::error($process->getErrorOutput());
            Log::error($process->getCommandLine());

            return collect(explode("\n", $process->getErrorOutput()))
                ->filter(fn($string) => Str::startsWith($string, 'pyxform.errors.PyXFormError:'))
                ->map(fn($string) => Str::replace('pyxform.errors.PyXFormError:', '', $string));
        }

        return true;
    }
}
