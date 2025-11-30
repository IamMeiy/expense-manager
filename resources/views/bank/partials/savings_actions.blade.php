<button data-url="{{ route('savings.edit',[$saving->bank_account_id, $saving->id]) }}" class="btn btn-primary edit-savings" >
    <i class="ti ti-edit"></i> Edit
</button>
<button class="btn btn-danger delete-savings"  data-url="{{ route('savings.destroy',[$saving->bank_account_id, $saving->id]) }}">
    <i class="ti ti-trash"></i> Delete
</button>
