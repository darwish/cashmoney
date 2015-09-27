import json
import collections

def solve(string):
    purchases = json.loads(string)

    # tallying
    shares = collections.defaultdict(float)
    for description, amount, buyer, beneficiaries in purchases:
        portion = float(amount) / len(beneficiaries)
        shares[buyer] += amount
        for user in beneficiaries:
            shares[user] -= portion

    # figuring out outflows
    transactions_required = []
    while shares:
        debtor = min(shares, key=shares.get)
        lender = max(shares, key=shares.get)
        payment = round(min(shares[lender], -shares[debtor]), 2)
        shares[lender] -= payment
        shares[debtor] += payment
        if payment:
            transactions_required.append([debtor, lender, payment])
        if abs(shares[lender]) < 1:
            del shares[lender]

    return json.dumps(transactions_required)


def test():
    purchases = [
        ('Gas', 300, 'Greencorn', ['Greencorn', 'Darwish']),
        ('Booze', 300, 'Darwish', ['Greencorn', 'Darwish', 'Andrew', 'Roderic']),
        ('Meat', 50, 'Darwish', ['Greencorn', 'Darwish', 'Hoffman', 'Andrew']),
        ('Other Food', 100, 'Darwish', ['Greencorn', 'Darwish', 'Hoffman', 'Andrew', 'Roderic']),
        ('Portobello Mushrooms', 50, 'Andrew', ['Greencorn', 'Andrew']),
    ]

    string = json.dumps(purchases)
    print(string)

    output = solve(string)
    print(output)

if __name__ == '__main__':
    import sys
    encoded = sys.argv[1]
    string = bytearray.fromhex(encoded).decode()
    print( solve(string) )
