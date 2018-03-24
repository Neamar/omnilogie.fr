import argparse

if __name__ == '__main__':
  parser = argparse.ArgumentParser()
  parser.add_argument('input_file', nargs='+', type=argparse.FileType('r'), default=None, help='The input file')

pizza = []
with open('small.in') as file:
  l = file.read().splitlines()
  r, c, l, h  = [int(i) for i in l[0].split(" ")]
  for row in l[1:]:
    pizza.append(row.split(""))
