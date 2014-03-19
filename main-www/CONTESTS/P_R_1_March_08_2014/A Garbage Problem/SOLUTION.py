__author__ = 'robertv'

import math
import sys


MAX_DISTANCE = 999999999
PRECISION = 0.0000001
MAX_SLOPE = 1/PRECISION

def distance(p1, p2):
    assert isinstance(p1, Point)
    assert isinstance(p2, Point)
    d_2 = (p2.x - p1.x)*(p2.x - p1.x) + (p2.y - p1.y)*(p2.y - p1.y)
    return math.sqrt(d_2)

def find_furthest_point(p, points):
    assert isinstance(p, Point)
    fp = None
    fd = 0
    for p2 in points:
        d = distance(p, p2)
        if d > fd:
            fp = p2
            fd = d
    return fp

def find_most_distance_pair(points):
    d = 0
    pair = (Point(0,0), Point(0,0))
    for p1 in points:
        for p2 in points:
            d12 = distance(p1, p2)
            if d12 > d:
                d = d12
                pair = (p1, p2)
    return pair

def find_midpoint(p1, p2):
    assert isinstance(p1, Point)
    assert isinstance(p2, Point)
    x = (p1.x + p2.x)/2.0
    y = (p1.y + p2.y)/2.0
    return Point(x,y)

def enclosing_rectangle(points):
    min_x = MAX_DISTANCE
    min_y = MAX_DISTANCE
    max_x = - MAX_DISTANCE
    max_y = - MAX_DISTANCE

    for p in points:
        if p.x < min_x:
            min_x = p.x
        if p.y < min_y:
            min_y = p.y
        if p.x > max_x:
            max_x = p.x
        if p.y > max_y:
            max_y = p.y
    return (Point(min_x, min_y), Point(max_x, max_y))


class Point:
    x = 0.0
    y = 0.0

    def __init__(self, x, y):
        self.x = x
        self.y = y

class Line:
    slope = 0.0
    intercept = 0.0

    # y = mx+b
    def __init__(self, m, b):
        self.slope = m
        self.intercept = b

    def intersects(self, l):
        assert isinstance(l, Line)
        if math.fabs(self.slope - l.slope) < PRECISION:
            if (l.intercept - self.intercept) > 0:
                x = MAX_DISTANCE
            else:
                x = -MAX_DISTANCE
        else:
            x = (l.intercept - self.intercept) / (self.slope - l.slope)
        y = self.slope * x + self.intercept
        return Point(x,y)

    # line defined by two points
    @staticmethod
    def defined_by(p1, p2):
        assert isinstance(p1, Point)
        assert isinstance(p2, Point)
        if math.fabs(p2.x - p1.x) < PRECISION:
            if (p2.y - p1.y) > 0:
                slope = MAX_SLOPE
            else:
                slope = -MAX_SLOPE
        else:
            slope = (p2.y - p1.y) / (p2.x - p1.x)
        intercept = p1.y - slope * p1.x
        return Line(slope, intercept)

    @staticmethod
    def cuts_through_with_slope(p, m):
        assert isinstance(p, Point)
        intercept = p.y - (m * p.x)
        return Line(m, intercept)

    @staticmethod
    def perpendicular_bisector(p1, p2):
        line = Line.defined_by(p1, p2)
        if math.fabs(line.slope) < PRECISION:
            slope_of_perpendicular = MAX_SLOPE
        else:
            slope_of_perpendicular = - (1.0 / line.slope)
        mid_point = Point((p1.x + p2.x)/2.0, (p1.y + p2.y)/2.0)
        return Line.cuts_through_with_slope(mid_point, slope_of_perpendicular)


class Circle:
    radius = 0.0
    center = Point(0.0, 0.0)

    def __init__(self, r, c):
        assert isinstance(c, Point)
        self.radius = r
        self.center = c

    # true if the point is in this circle
    def contains(self, p):
        assert isinstance(p, Point)
        d = distance(self.center, p)
        diff = d - self.radius
        if diff < PRECISION:
            return True
        return False

    def distance_from_center(self, p):
        assert isinstance(p, Point)
        return distance(self.center, p)

    def distance_from_circle(self, p):
        assert isinstance(p, Point)
        return distance(self.center, p) - self.radius

    def all_points_outside(self, points):
        fp = []
        for p in points:
            if not self.contains(p):
                fp.append(p)
        return fp

    def is_on_circumference(self, point):
        assert isinstance(point, Point)
        d = distance(self.center, point)
        if d < PRECISION:
            return True
        else:
            return False

    def point_furthest_from_circle(self, points):
        fd = 0
        fp = None
        for p in points:
            if not self.contains(p):
                d = self.distance_from_circle(p)
                if d > fd:
                    fp = p
                    fd = d
        return fp

    def points_furthest_from_center(self, points):
        fd = 0
        fp = None
        fd2 = 0
        fp2 = None
        for p in points:
            d = self.distance_from_center(p)
            if d > fd:
                fp2 = fp
                fd2 = fd
                fp = p
                fd = d
            elif d > fd2:
                fp2 = p
                fd2 = d
        return (fp, fp2)

    @staticmethod
    def circumscribed_by(p1, p2, p3):
        assert isinstance(p1, Point)
        assert isinstance(p2, Point)
        assert isinstance(p3, Point)
        l1 = Line.perpendicular_bisector(p1, p2)
        l2 = Line.perpendicular_bisector(p2, p3)
        center = l1.intersects(l2)
        radius = distance(p1, center)
        return Circle(radius, center)

    @staticmethod
    def from_two_radial_points(p1, p2):
        assert isinstance(p1, Point)
        assert isinstance(p2, Point)
        r = distance(p1, p2)/2.0
        center = find_midpoint(p1, p2)
        return Circle(r, center)

class House():
    location = Point(0,0)

    def __init__(self, loc):
        assert isinstance(loc, Point)
        self.location = loc

class DataSet():
    house_set = []

    def __init__(self):
        self.house_set = []

    def add_house(self, h):
        assert isinstance(h, House)
        self.house_set.append(h)

    def show(self):
        for h in self.house_set:
            print("{0:.3f} {1:.3f}").format(h.location.x, h.location.y)

def show_solution(data, mec):
    X = []
    Y = []
    for d in data:
        assert isinstance(d, Point)
        X.append(d.x)
        Y.append(d.y)
    plt.plot(X, Y, 'ro')
    mec_draw = plt.Circle((mec.center.x, mec.center.y),mec.radius, color='b', fill=False)
    fig = plt.gcf()
    fig.gca().add_artist(mec_draw)
    plt.axis([0.0, 3.0, 0.0, 3.0])
    plt.show()


def move_towards_point(c, p, points):
    assert isinstance(c, Circle)
    assert isinstance(p, Point)
    target_center = p
    start_center = c.center
    distance_to_move = distance(c.center, target_center)
    while distance_to_move > PRECISION:
        new_center = find_midpoint(c.center, target_center)
        new_circle = Circle(c.radius, new_center)
        (furthest, nf) = new_circle.points_furthest_from_center(points)
        new_circle.radius = distance(furthest, new_center)
        if new_circle.radius > c.radius:
            # can't go that far
            target_center = new_center
        else:
            # Set up new target as most distance point
            #target_center = find_midpoint(p, furthest)
            c.radius = new_circle.radius
            c.center = new_circle.center
        distance_to_move = distance(c.center, target_center)

        #print("MEC.r : {0} MEC.c : {1}, {2}").format(c.radius, c.center.x, c.center.y)
        #show_solution(points, c)
    distance_moved = distance(start_center, c.center)
    if distance_moved == 0:
        # didn't move, maybe we can shrink
        (furthest, nf) = c.points_furthest_from_center(points)
        radius = distance(furthest, c.center)
        if radius < c.radius:
            c.radius = radius
    return distance_moved

def shrink_circle(c, points):
    assert isinstance(c, Circle)
    distance_moved = 99999
    loop = 0
    while distance_moved > PRECISION:
        (furthest_point, next_furthest) = c.points_furthest_from_center(points)
        target = find_midpoint(furthest_point, next_furthest)
        distance_moved = move_towards_point(c, target, points)

        # Make sure we're running OK
        #(furthest_point, nf) = c.points_furthest_from_center(points)
        #assert c.radius == distance(furthest_point, c.center)
        loop += 1
        if loop > 100:
            print("TOO MANY LOOPS")
            break

def attempt_to_build_circle(points):
    (low_left, up_right ) = enclosing_rectangle(points)
    center = find_midpoint(low_left, up_right)
    X = up_right.x - low_left.x
    Y = up_right.y - low_left.y
    radius = math.sqrt(X*X + Y*Y)/2.0
    mec = Circle(radius, center)
    shrink_circle(mec, points)
    return mec


class MinimumEnclosingCircle():
    enclosed_points = []
    enclosing_circle = Circle(0.0, Point(0.0, 0.0))

    def __init__(self):
        self.enclosed_points = []

    def add_point(self, p_new):
        assert isinstance(p_new, Point)
        self.enclosed_points.append(p_new)

    def find_mec_from_exactly_3_points(self):
        assert len(self.enclosed_points) == 3
        (p1, p2) = find_most_distance_pair(self.enclosed_points)
        mec = Circle.from_two_radial_points(p1, p2)
        if mec.all_points_outside(self.enclosed_points) == []:
            self.enclosing_circle = mec
        else:
            p1 = self.enclosed_points[0]
            p2 = self.enclosed_points[1]
            p3 = self.enclosed_points[2]
            self.enclosing_circle = Circle.circumscribed_by(p1, p2, p3)


    def find_mec(self):
        if len(self.enclosed_points) == 0:
            self.enclosing_circle = Circle(0, Point(0,0))
        elif len(self.enclosed_points) == 1:
            p = self.enclosed_points[0]
            self.enclosing_circle = Circle(0.0, p)
        elif len(self.enclosed_points) == 2:
            p1 = self.enclosed_points[0]
            p2 = self.enclosed_points[1]
            self.enclosing_circle = Circle.from_two_radial_points(p1, p2)
        else:
            self.enclosing_circle = attempt_to_build_circle(self.enclosed_points)

DATA_COUNT_LINE=0
HOUSE_COUNT_LINE=1
HOUSE_LINE=2

def read_data():
    line_type = DATA_COUNT_LINE
    data_set_count = 0
    house_count = 0
    data_sets = []
    d = None

    for line in sys.stdin:
        if line == "":
            pass
        if line_type == HOUSE_LINE:
            (xs, ys) = line.split()
            h = House(Point(float(xs), float(ys)))
            d.add_house(h)
            if len(d.house_set) == house_count:
                if d != None:
                    data_sets.append(d)
                line_type = HOUSE_COUNT_LINE
        elif line_type == HOUSE_COUNT_LINE:
            house_count = int(line)
            d = DataSet()
            line_type = HOUSE_LINE
        elif line_type == DATA_COUNT_LINE:
            data_set_count = int(line)
            line_type = HOUSE_COUNT_LINE

    if len(data_sets) != data_set_count:
        print("Count mismatch, expected {0}, but got {1}").format(data_set_count, len(data_sets))
    return data_sets

def process_datum(d):
    mec = MinimumEnclosingCircle()
    for h in d.house_set:
        #print "X:{0}, Y:{1}".format(h.location.x, h.location.y)
        mec.add_point(h.location)
    mec.find_mec()
    return mec.enclosing_circle

def main():
    data_sets = read_data()
    scenario_number = 1
    for data_set in data_sets:
        #print "Scenario #{0} items".format(scenario_number)
        c = process_datum(data_set)
        d = 2.0 * c.radius
        x = c.center.x
        y = c.center.y
        print("Frost should put a dome {0:.3f} feet in diameter at ({1:.3f}, {2:.3f})").format(d, x, y)
        print
        scenario_number += 1


if __name__ == "__main__":
    main()
