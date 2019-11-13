<?php

namespace App;

use App\Person;
use Illuminate\Http\Request;
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
    public static function report(Request $request, Person $person)
    {
        $refunds = Refund::wherePersonId($person->id)
        ->whereMonth('date', $request->month)
        ->whereYear('date', $request->year)
        ->get();


        if ($refunds->isNotEmpty()) {
            $report = $refunds->first();
            
            $report->month = $request->month;
            $report->year = $request->year;
            $report->totalRefunds = $refunds->sum('value');
            $report->refunds = $refunds->count();

            return $report;
        }
    }

    public function export()
    {
        $owner = $this->getOwner();

        $this->toArray();

        $report = $this->only('month', 'year', 'totalRefunds', 'refunds');

        $keys = array_keys($report);

        $fileName = storage_path() . "\\app\\report_{$owner}_{$this->month}_{$this->year}.csv";

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

    public function imageUpload(Request $request)
    {
        $request->validate([
            'image' => 'required|image'
        ]);

        $ext = $request->image->extension();

        $image = $request->file('image')->storeAs('vouchers', $this->id . '.' . $ext);

        $this->image = $image;

        $this->save();

        return $this->image;
    }

    public static function filter(Request $request, Person $person)
    {
        return Refund::wherePersonId($person->id)
        ->whereMonth('date', $request->month)
        ->whereYear('date', $request->year)
        ->get();
    }

    public static function perPage($limit)
    {
        $refunds = Refund::paginate($limit);

        foreach ($refunds->items() as $refund) {
            $refund->setDateFormat(\DateTime::ATOM);
        }

        return $refunds;
    }

    public function setDateFormat($format)
    {
        $this->date = \Carbon\Carbon::parse($this->date)->format($format);

        $this->makeHidden('deleted_at');
    }
}
