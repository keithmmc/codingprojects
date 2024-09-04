import random, sys 
JAPANESE_NUMBERS = {1: 'ICHI', 2: 'NI', 3: 'SAN',
                      4: 'SHI', 5: 'GO', 6: 'ROKU'}

purse = 5000 
while True:
    print('You have', purse, 'how much do you want to bet?(or QUIT)')
    while True:
        pot = input('>')
        if pot.upper() == 'QUIT':
            print('thanks for playing')
            sys.exit()
        elif not pot.isdecimal():
            print('please enter a number')
        elif int(pot) > purse:
            print('You do not have to make this bet')
        else:
            pot = int(pot)
            break 
    
