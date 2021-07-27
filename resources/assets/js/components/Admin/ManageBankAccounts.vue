<template>
    <div>
        <add-bank-account ref="addBankAccountsRef"></add-bank-account>
        <bank-account-lists ref="bankAccountListsRef"></bank-account-lists>
    </div>
</template>

<script>
import AddBankAccount from './AddBankAccount';
import BankAccountLists from './BankAccountLists';
export default {
    components: {
        AddBankAccount,
        BankAccountLists
    }
}
</script>

<style>

</style>