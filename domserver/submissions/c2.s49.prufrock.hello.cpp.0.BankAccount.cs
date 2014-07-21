namespace BankAccountApplication
{
    public class BankAccount : IBankAccount
    {
        public const decimal MinimumBalance = 50.0M;

        public BankAccount(decimal accountBalance = MinimumBalance)
        {
            if (accountBalance < MinimumBalance)
            {
                throw new InsufficientInitialFundsException();
            }

            AccountBalance = accountBalance;
        }

        public decimal AccountBalance { get; private set; }
        public bool IsPremium { get { return AccountBalance >= 500.00M; } }

        public bool Deposit(decimal depositAmount)
        {
            if (depositAmount <= 0.0M)
            {
                throw new InvalidTransactionException();
            }

            AccountBalance += depositAmount;
            return true;
        }

        public bool Withdraw(decimal withdrawAmount)
        {
            if (withdrawAmount <= 0.00M ||
                withdrawAmount > AccountBalance - MinimumBalance)
            {
                throw new InvalidTransactionException();
            }

            AccountBalance -= withdrawAmount;
            return true;
        }
    }
}
