    @csrf

    <div class="col-sm-2">
        <div class="form-group">
            <label for="start_date" class="form-label mt-1">From</label>
            <input type="date" class="form-control" name="start_date" value="{{ date('Y-m-d') }}" placeholder="Start date">
        </div>
    </div>

    <div class="col-sm-2">
        <div class="form-group">
            <label for="end_date" class="form-label mt-1">To</label>
            <input type="date" class="form-control" name="end_date"  value="{{ date('Y-m-d') }}"  placeholder="End date">
        </div>
    </div>

    <div class="col-sm-4">
        <div class="form-group">
            <label class="form-label mt-1 w-100"> &nbsp;</label>
            <btn-submit class="btn btn-success text-light">
                Generate report
            </btn-submit>
        </div>
    </div>
