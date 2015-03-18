__author__ = 'robertv'

import sys

def get_integer_from_data_source(data_source):
    input = data_source.readline()
    number_of_contests = int(input)
    return number_of_contests

def get_winning_score(data_source):
    best_score = -1
    number_of_contestants = get_integer_from_data_source(data_source)
    for contestant in range(0, number_of_contestants):
        score = get_integer_from_data_source(data_source)
        if best_score < score:
            best_score = score
    return best_score


def main():
    data_source = sys.stdin
    #data_source = open("winner.in")
    number_of_contests = get_integer_from_data_source(data_source)
    for contest in range(0,number_of_contests):
        winner = get_winning_score(data_source)
        print("The winning score is... {0} points!".format(winner))

if __name__ == "__main__":
    main()
