<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $guarded = [];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Updates the refunds value.
     *
     * @param float $value
     * @return bool
     */
    public function rectify(float $value)
    {
        if (!$this->isApproved()) {
            $this->value = $value;

            $this->save();
        }

        return $this->isApproved();
    }

    /**
     * Approve the specified refund.
     *
     * @return \App\Refund
     */
    public function approve()
    {
        $this->approved = true;

        $this->save();

        return $this;
    }

    /**
     * Returns the current status of the specified refund.
     *
     * @return boolean
     */
    public function isApproved()
    {
        return $this->approved;
    }

    /**
     * Shows a monthly report of the given person.
     *
     * @param  int  $month,  int  $year
     * @return \App\Person
     */
    public static function report(Person $person, int $year, int $month)
    {
        $refunds = Refund::wherePersonId($person->id)
        ->whereYear('date', $year)
        ->whereMonth('date', $month)->first();

        if (!empty($refunds)) {
            $refunds->totalRefunds = $refunds->count();
            $refunds->refunds = $refunds->sum('value');
            $refunds->month = $month;
            $refunds->year = $year;

            return $refunds;
        }
    }

    public function export()
    {
        $owner = $this->getOwner();

        $this->toArray();

        $report = $this->only('month', 'year', 'totalRefunds', 'refunds');

        $keys = array_keys($report);

        $fileName = storage_path()."\\app\\report_{$owner}_{$this->month}_{$this->year}.csv";

        return $this->toCSV($fileName, $keys, $report);
    }

    /**
     * Returns the refund owner's name in snake case.
     *
     * @return string
     */
    public function getOwner()
    {
        $this->makeHidden(['person']);

        $owner = preg_replace('/ /', '_', $this->person->name);

        return mb_strtolower($owner);
    }

    /**
     * Creates the reports CSV file.
     *
     * @return string
     */
    public function toCSV(string $fileName, array $keys, $report)
    {
        $fo = fopen($fileName, 'w');

        fputcsv($fo, $keys);

        fputcsv($fo, $report);
        
        fclose($fo);

        return $fileName;
    }

    public function imageUpload($request)
    {
        $request->validate([
            'image' => 'required|image'
        ]);

        $ext = $request->image->extension();

        return $request->file('image')->storeAs('vouchers', $this->id.'.'.$ext);
    }
}
