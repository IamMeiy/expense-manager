<button data-url="{{ route('bank-accounts.edit', $bankAccount->id) }}" class="btn btn-primary edit-bank-account" >
    <i class="ti ti-edit"></i> Edit
</button>
<button class="btn btn-danger delete-bank-account"  data-url="{{ route('bank-accounts.destroy', $bankAccount->id) }}">
    <i class="ti ti-trash"></i> Delete
</button>
