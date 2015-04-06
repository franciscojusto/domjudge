#author: david xin (david_xin@ultimatesoftware.com)
#date: 3/23/2015

import sys
import fileinput

# Solves the pachinko problem by building a directed acyclic graph
# starting at peg 0, finding a topological sorting for said graph,
# and then dynamically going through each peg in this order and updating
# its maximal value so far. The final solution will be stored in the
# final peg at the end of the algorithm. This will take O(P) time since
# each individual step takes O(P) time and the steps are independent.
def pachinko():

    

        inputFile = sys.stdin;

        # Get the number of problems, which is the first number in the file
        numberOfProblems = int(inputFile.readline())

        # Loop through the problems and solve each one
        for problem in range(numberOfProblems):
        
            # Get the number of pegs for this problem
            numberOfPegs = int(inputFile.readline())

            # Read in the "children" of each peg, storing results in an array.
            # We need to do this because peg indices are arbitrary, so we may need
            # to skip from a higher indexed peg to a lower indexed one, as long as
            # this does not cause a loop or revisiting a node.
            pegList = []
            for peg in range(numberOfPegs):
                # Read in the pegs that the current peg falls to
                nextPeg = inputFile.readline()
                leftPeg = int(nextPeg.split(" ")[0])
                rightPeg = int(nextPeg.split(" ")[1])
                pegList.append([leftPeg, rightPeg])

            # Find the best processing order by topologically sorting
            # the pegs so that no peg is updated before everything its
            # value might depend on is updated.
            processingOrder = topological_sort(pegList)
            
            # Initialize an array to hold the maximum value computed so far for a
            # specific peg. Each peg starts off at -1, as no peg is reached yet.
            # We want numberOfPegs + 1 because the final "peg" will represent
            # falling out of the box and will hold the total score at the end.
            maxValuePerPeg = [-1] * (numberOfPegs + 1)

            # Initialize the 0th peg manually, as this will be the only reachable
            # node at the start. We will use the value -1 to test if a node is
            # unreachable from 0th peg.
            maxValuePerPeg[0] = ""
        
            # Loop through each peg, updating
            for peg in processingOrder:

                # Get the current value of the peg
                currentPegValue = maxValuePerPeg[peg]

                # Get the children peg from the peg list
                leftPeg = pegList[peg][0]
                rightPeg = pegList[peg][1]
                
                # Update the left peg's value, if it is not a wall
                if leftPeg != -1:
                    currentLeftValue = maxValuePerPeg[leftPeg]
                    potentialLeftValue = "1" + currentPegValue
                    # Updates with new value if left peg is reached for the first time, of if the new
                    # value is greater than the previous value.
                    # Note: int(string x, 2) converts a string x containing a binary string into the
                    # integer representation of that binary string.
                    if currentLeftValue == -1 or int(potentialLeftValue, 2) > int(currentLeftValue, 2):
                        maxValuePerPeg[leftPeg] = potentialLeftValue
                        
                # Update the right peg's value, if it is not a wall
                if rightPeg != -1:
                    currentRightValue = maxValuePerPeg[rightPeg]
                    potentialRightValue = "0" + currentPegValue
                    # Updates with new value if right peg is reached for the first time, or if the new
                    # value is greater than the previous value.
                    # Note: int(string x, 2) converts a string x containing a binary string into the
                    # integer representation of that binary string.
                    if currentRightValue == -1 or int(potentialRightValue, 2) > int(currentRightValue, 2):
                        maxValuePerPeg[rightPeg] = potentialRightValue

            # Prints out the final value of the last peg as an integer
            # This is the max score obtainable. If the last peg
            # is unreachable, return 0.
            finalScore = maxValuePerPeg[numberOfPegs]
            if finalScore == -1 or numberOfPegs == 0:
                print(0)
            else:
                print(int(finalScore, 2))

        sys.stdout.flush()

# Method to return the topological order of a directed acyclic graph.
# This method is customized to our use as it starts off a queue with
# only pegs 0, as opposed to all pegs with no in-edges. This is because
# we will never reach those other pegs, so we do not want to return
# them for our processing order.
def topological_sort(unsortedGraph):
    # Array to hold the number of in-edges for each peg.
    inEdgesPerPeg = [0] * len(unsortedGraph)

    # Fills up array, adding one to the number of in-edges for
    # each "child" peg that a peg falls to. We do not care about walls
    # or the "falling out" index.
    for peg in unsortedGraph:
        leftChild = peg[0]
        if leftChild != -1 and leftChild != len(unsortedGraph):
            inEdgesPerPeg[leftChild] += 1
        rightChild = peg[1]
        if rightChild != -1 and rightChild != len(unsortedGraph):
            inEdgesPerPeg[rightChild] += 1
    
    # Start the processing queue at peg 0, and store the final
    # processing order in an array
    queue = [0]
    processingOrder = []
    
    while queue:
        # Add first peg of queue to processing order result
        pegToProcess = queue.pop(0)
        processingOrder.append(pegToProcess)
        # Since peg is "removed", its children have one less in-edge.
        # If this makes the child end up with no in-edges, it can
        # then be added to the queue. We do not care about walls
        # or the "falling out" index.
        leftChild = unsortedGraph[pegToProcess][0]
        if leftChild != -1 and leftChild != len(unsortedGraph):
            inEdgesPerPeg[leftChild] -= 1
            if inEdgesPerPeg[leftChild] == 0:
                queue.append(leftChild)
        rightChild = unsortedGraph[pegToProcess][1]
        if rightChild != -1 and rightChild != len(unsortedGraph):
            inEdgesPerPeg[rightChild] -= 1
            if inEdgesPerPeg[rightChild] == 0:
                queue.append(rightChild)
                
    return processingOrder
        
    
pachinko()
