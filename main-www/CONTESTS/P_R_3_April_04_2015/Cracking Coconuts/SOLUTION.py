__author__ = 'robertv'

import sys
import math
from math import pi, sin, cos

TOLERANCE = 0.00001


class Point:
    x = 0.0
    y = 0.0
    z = 0.0

    def __init__(self, xx, yy, zz):
        self.x = float(xx)
        self.y = float(yy)
        self.z = float(zz)

    def same_point(self, p):
        dx = math.fabs(p.x - self.x)
        dy = math.fabs(p.y - self.y)
        dz = math.fabs(p.z - self.z)
        return dx < TOLERANCE and dy < TOLERANCE and dz < TOLERANCE

class Edge:
    pa = Point(0,0,0)
    pb = Point(0,0,0)

    def __init__(self, p1, p2):
        self.pa = p1
        self.pb = p2

    def is_colinear(self, p):
        if self.pa.same_point(p):
            return True
        v1 = Vector(self.pa, self.pb)
        v2 = Vector(self.pa, p)
        angle = v1.angle(v2)
        if math.fabs(angle) < TOLERANCE:
            return True
        if math.fabs(angle - 180) < TOLERANCE:
            return True
        return False


class Vector:
    x = 0.0
    y = 0.0
    z = 0.0

    def __init__(self, p1, p2):
        self.x = p2.x - p1.x
        self.y = p2.y - p1.y
        self.z = p2.z - p1.z

    def magnitude(self):
        d_2 = self.x * self.x + self.y* self.y + self.z * self.z
        m = math.sqrt(d_2)
        return m

    def dot(self, v):
        dot = self.x*v.x + self.y*v.y + self.z*v.z
        return dot

    def cross(self, v):
        x = self.y * v.z - self.z * v.y
        y = - (self.x * v.z - self.z * v.x)
        z = self.x * v.y - self.y * v.x
        return Vector(Point(0,0,0), Point(x,y,z))

    def angle(self, v):
        """
        @param v: vector
        @return: angle, in degrees, between this and v.
        """
        cos_angle = self.dot(v)/(self.magnitude() * v.magnitude())
        # rounding error may push cos_angle slightly higher than 1.0
        cos_angle = self._force_cos_into_range(cos_angle)
        theta = math.acos(cos_angle)
        tt = 360 * theta / (2*math.pi)
        return tt

    def _force_cos_into_range(self, cos_angle):
        if (cos_angle > 1.0):
            return 1.0
        if (cos_angle < -1.0):
            return -1.0
        return cos_angle
#
# The points of a Hull Facet are arranged counter-clockwise
class HullFacet:
    marked_for_removal = False
    __points = []

    #Equation of the plane on which this face exists:
    # Ax + By + Cz + D = 0
    _A = 0.0
    _B = 0.0
    _C = 0.0
    _D = None

    def __init__(self, points):
        if len(points) < 3:
            raise "Hull Facet needs at least 3 points"
        self.__points = points
        self.__find_encompassing_plane()
        self.marked_for_removal = False

    def get_points(self):
        return self.__points

    def contains_point(self, p):
        point_index = None
        for p_index in range(0, len(self.__points)):
            if self.__points[p_index].same_point(p):
                point_index = p_index
                break
        return point_index

    def vectors_originating_at(self, p):
        num_points = len(self.__points)
        v1 = None
        v2 = None

        p_index = self.contains_point(p)
        if p_index == None:
            return (v1, v2)

        p_next = (p_index + 1) % num_points
        p_prev = (p_index - 1) % num_points
        v1 = Vector(self.__points[p_index], self.__points[p_prev])
        v2 = Vector(self.__points[p_index], self.__points[p_next])
        return (v1,v2)

    def is_coplanar(self, p):
        side = self.__side_of_plane(p)
        return math.fabs(side) < TOLERANCE

    def is_facet_coplanar(self, facet):
        coplanar = True
        for p in facet.get_points():
            if not self.is_coplanar(p):
                coplanar = False
                break
        return coplanar

    def are_on_same_side(self, p1, p2):
        s1 = self.__side_of_plane(p1)
        s2 = self.__side_of_plane(p2)
        if s1 > 0 and s2 > 0:
            return True
        if s1 < 0 and s2 < 0:
            return True
        return False

    def __find_encompassing_plane(self):
        v1 = Vector(self.__points[0], self.__points[1])
        v2 = Vector(self.__points[0], self.__points[2])
        cross = v1.cross(v2)
        self._A = cross.x
        self._B = cross.y
        self._C = cross.z
        p = self.__points[0]
        self._D = -(self._A * p.x + self._B * p.y + self._C * p.z)

    def __side_of_plane(self, p):
        side = self._A * p.x + self._B * p.y + self._C * p.z + self._D
        return side

def point_is_contained_in_hull_of_facets(facets, centroid, p):
    """
        returns True if point is within a hull, or on its surface
        Otherwise returns a list of visible facets
    @param facets:
    @param centroid:
    @param p:
    @return:
    """
    faces_visible_to_point = []
    for f in facets:
        if f.is_coplanar(p):
            continue
        if f.are_on_same_side(p, centroid):
            continue
        faces_visible_to_point.append(f)

    if len(faces_visible_to_point) == 0:
        return (True, faces_visible_to_point)
    else:
        return (False, faces_visible_to_point)

def remove_colinear_points(points):
    """
    Assumes points are the edge of a facet and are counter-clockwise
    If a point is colinear to its previous and next point, it should be
    removed.  CCW assumption ensures it is between the two, not past them
    @param points:
    @return:
    """
    clean_points = []
    num_points = len(points)
    for index in range(0, num_points):
        e1 = Edge(points[(index-1)%num_points], points[(index+1)%num_points])
        if not e1.is_colinear(points[index]):
            clean_points.append(points[index])
    return clean_points

def remove_interior_facet(facets):
    exterior_facets = []
    for index in range(0, len(facets)):
        f1 = facets[index]
        if f1.marked_for_removal:
            continue
        for index2 in range(index+1, len(facets)):
            f2 = facets[index2]
            if f1.is_facet_coplanar(f2):
                f1.marked_for_removal = True
                f2.marked_for_removal = True

    for f in facets:
        if not f.marked_for_removal:
            exterior_facets.append(f)
    return exterior_facets


class ConvexHullBuilder:
    __points = []
    __facets = []
    __centroid_sum_X = 0.0
    __centroid_sum_Y = 0.0
    __centroid_sum_Z = 0.0
    __num_points_in_current_hull = 0

    def __init__(self, points):
        self.__facets = []
        self.__points = points
        self.__num_points_in_current_hull = 0
        self.__centroid_sum_X = 0.0
        self.__centroid_sum_Y = 0.0
        self.__centroid_sum_Z = 0.0
        self.__generate_facets()

    def get_facets(self):
        return self.__facets


    @property
    def centroid_of_current_hull(self):
        x = self.__centroid_sum_X / self.__num_points_in_current_hull
        y = self.__centroid_sum_Y / self.__num_points_in_current_hull
        z = self.__centroid_sum_Z / self.__num_points_in_current_hull
        return Point(x,y,z)


    def __generate_facets(self):
        if len(self.__points) < 4:
            return

        self.__add_initial_facets()

        for point in self.__points:
            self.__add_point_and_regenerate_hull(point)

    def __find_min_max_points(self):
        min = 10000.0
        max = -10000.0
        min_p = None
        max_p = None
        for p in self.__points:
            v = Vector(p, Point(0,0,0))
            m = v.magnitude()
            if m < min:
                min = m
                min_p = p
            if m > max:
                max = m
                max_p = p
        return (min_p, max_p)

    ##
    # Find 4 points to build the initial convex hull.  This will be a 4-sided solid (Tetrahedron)
    #
    def __add_initial_facets(self):
        (p1,p2) = self.__find_min_max_points()
        p1 = self.__points[0]
        p2 = None
        p3 = None
        p4 = None
        e1 = Edge(p1, p2)
#        e1 = None
        f1 = None

        for point_index in range(1, len(self.__points)):
            p = self.__points[point_index]
            if p2 == None:
                # Find a point that is not the same as p1
                if not p1.same_point(p):
                    p2 = p
                    e1 = Edge(p1, p2)
            elif p3 == None:
                # Find a point that is not co-linear to p1 and p2
                if not e1.is_colinear(p):
                    p3 = p
                    f1 = HullFacet([p1, p2, p3])
            elif p4 == None:
                # Find a point that is not co-planar to p1, p2, p3
                if not f1.is_coplanar(p):
                    p4 = p
                    self.__add_facet(f1)
                    self.__add_facet(HullFacet([p1, p2, p4]))
                    self.__add_facet(HullFacet([p1, p3, p4]))
                    self.__add_facet(HullFacet([p2, p3, p4]))
                    self.__add_point_to_current_hull(p1)
                    self.__add_point_to_current_hull(p2)
                    self.__add_point_to_current_hull(p3)
                    self.__add_point_to_current_hull(p4)
                    break

    def __add_facet(self, new_facet):
        self.__facets.append(new_facet)

    def __add_facet_and_merge(self, new_facet):
        for f in self.get_facets():
            if f.is_facet_coplanar(new_facet):
                self.__merge_facets(f, new_facet)
                break
        else:
            self.__facets.append(new_facet)

    #
    # We know we only add triangular facets
    # and the first two points must match the existing facet
    # and the last point is coplanar
    def __merge_facets(self, existing_facet, new_facet):
        cur_points = existing_facet.get_points()
        num_points = len(cur_points)
        new_points = new_facet.get_points()

        p1_index = existing_facet.contains_point(new_points[0])
        p2_index = existing_facet.contains_point(new_points[1])
        assert(not p1_index == None)
        if p2_index == None:
            raise "cannot find adjoining points"
        assert(existing_facet.is_coplanar(new_points[2]))

        points = []
        if (((p1_index+1) % num_points) == p2_index):
            insert_point = p1_index
        elif(((p2_index+1) % num_points) == p1_index):
            insert_point = p2_index
        else:
            raise "Invalid insert point"

        for index in range(0, num_points):
            points.append(cur_points[index])
            if index == insert_point:
                points.append(new_points[2])

        self.__facets.remove(existing_facet)
        clean_points = remove_colinear_points(points)
        self.__add_facet(HullFacet(clean_points))

    def __add_point_to_current_hull(self, p):
        self.__centroid_sum_X += p.x
        self.__centroid_sum_Y += p.y
        self.__centroid_sum_Z += p.z
        self.__num_points_in_current_hull += 1

    def __add_point_and_regenerate_hull(self, p):
        (inside, visible_facets) = point_is_contained_in_hull_of_facets(self.__facets, self.centroid_of_current_hull, p)
        if inside:
            return
        facets_to_add = []
        for facet_to_remove in visible_facets:
            self.__facets.remove(facet_to_remove)
            points = facet_to_remove.get_points()
            num_points = len(points)
            for i in range(0, num_points):
                p1 = points[i]
                p2 = points[(i+1) % num_points]
                p3 = p
                f = HullFacet([p1, p2, p3])
                facets_to_add.append(f)

        # If a facet is in the list twice, it is interior and should be removed
        cleaned_facets_to_add = remove_interior_facet(facets_to_add)
        for f in cleaned_facets_to_add:
            self.__add_facet_and_merge(f)
        self.__add_point_to_current_hull(p)



class ConvexHull:
    __points = []
    __facets = []
    __builder = None
    __centroid = None

    def __init__(self, points):
        self.__points = points
        self.__builder = ConvexHullBuilder(points)
        self.__facets = self.__builder.get_facets()
        self.__centroid = self.__builder.centroid_of_current_hull

    #
    #  Use this if you already have the facets for the convex hull
    def set_facets(self, facets):
        self.__facets = facets
        self.__centroid = self.__centroid_of_all_points

    def point_is_contained_in_hull(self, p):
        (is_in, facets) = point_is_contained_in_hull_of_facets(self.__facets, self.__centroid, p)
        return is_in

    @property
    def minimum_average_angle(self):
        minimum_angle = 180.00
        average = 180.0
        for p in self.__points:
            facets_at_p = self.__get_facets_containing_point(p)
            facets_found = len(facets_at_p)
            if (facets_found) > 0:
                sum_angles = 0.0
                for face in facets_at_p:
                    (v1,v2) = face.vectors_originating_at(p)
                    angle = math.fabs(v1.angle(v2))
                    sum_angles += angle
                average = sum_angles / facets_found
            if minimum_angle > average:
                minimum_angle = average
        return minimum_angle


    def __get_facets_containing_point(self, p):
        facets_at_p = []
        for facet in self.__facets:
            if not facet.contains_point(p) == None:
                facets_at_p.append(facet)
        return facets_at_p

    @property
    def __centroid_of_all_points(self):
        x = 0
        y = 0
        z = 0
        num_points = len(self.__points)
        for p in self.__points:
            x += p.x
            y += p.y
            z += p.z
        x = x / num_points
        y = y / num_points
        z = z / num_points
        return Point(x, y, z)


class Cube:
    __side = 1.0
    __origin = Point(0.0, 0.0, 0.0)
    __points = []

    def __init__(self, side):
        self.__side = side
        self.__points = []
        self.__set_points()

    def convex_hull_from_faces(self):
        ch = ConvexHull(self.__points)
        faces = []
        faces.append(self.__bottom_face())
        faces.append(self.__top_face())
        faces.append(self.__back_face())
        faces.append(self.__front_face())
        faces.append(self.__left_face())
        faces.append(self.__right_face())
        ch.set_facets(faces)
        return ch

    def convex_hull_from_points(self):
        ch = ConvexHull(self.__points)
        return ch

    def get_points(self):
        return self.__points

    def __set_points(self):
        ox = self.__origin.x
        oy = self.__origin.y
        oz = self.__origin.z
        dx = self.__side
        dy = self.__side
        dz = self.__side
        self.__points.append(Point(ox, oy, oz))
        self.__points.append(Point(ox, oy, oz + dz))
        self.__points.append(Point(ox, oy + dy, oz))
        self.__points.append(Point(ox, oy + dy, oz+ dz))
        self.__points.append(Point(ox + dx, oy, oz))
        self.__points.append(Point(ox + dx, oy, oz + dz))
        self.__points.append(Point(ox + dx, oy + dy, oz))
        self.__points.append(Point(ox + dx, oy + dy, oz + dz))

    def __bottom_face(self):
        points = []
        points.append(self.__points[0])
        points.append(self.__points[4])
        points.append(self.__points[6])
        points.append(self.__points[2])
        f = HullFacet(points)
        return f

    def __top_face(self):
        points = []
        points.append(self.__points[1])
        points.append(self.__points[5])
        points.append(self.__points[7])
        points.append(self.__points[3])
        f = HullFacet(points)
        return f

    def __back_face(self):
        points = []
        points.append(self.__points[0])
        points.append(self.__points[2])
        points.append(self.__points[3])
        points.append(self.__points[1])
        f = HullFacet(points)
        return f

    def __front_face(self):
        points = []
        points.append(self.__points[4])
        points.append(self.__points[6])
        points.append(self.__points[7])
        points.append(self.__points[5])
        f = HullFacet(points)
        return f

    def __left_face(self):
        points = []
        points.append(self.__points[0])
        points.append(self.__points[4])
        points.append(self.__points[5])
        points.append(self.__points[1])
        f = HullFacet(points)
        return f

    def __right_face(self):
        points = []
        points.append(self.__points[2])
        points.append(self.__points[6])
        points.append(self.__points[7])
        points.append(self.__points[3])
        f = HullFacet(points)
        return f


class Half_Cube:
    __side = 1.0
    __origin = Point(0.0, 0.0, 0.0)
    __points = []

    def __init__(self, side):
        self.__side = side
        self.__set_points()

    def convex_hull_from_faces(self):
        ch = ConvexHull(self.__points)
        faces = []
        faces.append(self.__bottom_face())
        faces.append(self.__top_face())
        faces.append(self.__back_face())
        faces.append(self.__left_face())
        faces.append(self.__diagonal_face())
        ch.set_facets(faces)
        return ch

    def convex_hull_from_points(self):
        ch = ConvexHull(self.__points)
        return ch

    def __set_points(self):
        ox = self.__origin.x
        oy = self.__origin.y
        oz = self.__origin.z
        dx = self.__side
        dy = self.__side
        dz = self.__side
        self.__points.append(Point(ox, oy, oz))
        self.__points.append(Point(ox, oy, oz + dz))
        self.__points.append(Point(ox, oy + dy, oz))
        self.__points.append(Point(ox, oy + dy, oz+ dz))
        self.__points.append(Point(ox + dx, oy, oz))
        self.__points.append(Point(ox + dx, oy, oz + dz))

    def __back_face(self):
        points = []
        points.append(self.__points[0])
        points.append(self.__points[2])
        points.append(self.__points[3])
        points.append(self.__points[1])
        f = HullFacet(points)
        return f

    def __left_face(self):
        points = []
        points.append(self.__points[0])
        points.append(self.__points[4])
        points.append(self.__points[5])
        points.append(self.__points[1])
        f = HullFacet(points)
        return f

    def __bottom_face(self):
        points = []
        points.append(self.__points[0])
        points.append(self.__points[4])
        points.append(self.__points[2])
        f = HullFacet(points)
        return f

    def __top_face(self):
        points = []
        points.append(self.__points[1])
        points.append(self.__points[5])
        points.append(self.__points[3])
        f = HullFacet(points)
        return f

    def __diagonal_face(self):
        points = []
        points.append(self.__points[2])
        points.append(self.__points[3])
        points.append(self.__points[5])
        points.append(self.__points[4])
        f = HullFacet(points)
        return f


def generate_random_solids():
    points = Cube(1.0).get_points()
    for i in range(0, 100):
        x = random.uniform(0,1.0)
        y = random.uniform(0, 1.0)
        z = random.uniform(0, 1.0)
        p = Point(x,y,z)
        points.append(p)
    rotation = Point(pi/2, pi/2, pi/4)
    scale = Point(2,10,2)
    translation = Point(0, 0, 0)
    t_points = __transform_points(points, rotation, scale, translation)
    __print_points(t_points)

def __print_points(points):
    print("{0}".format(len(points)))
    for p in points:
        print("{0:.3f} {1:.3f} {2:.3f}".format(p.x, p.y, p.z))


def __transform_points(points, rotation, scale, translation):
    translated_points = []
    for p in points:
        r = __rotate_point(p, rotation)
        t = __translate_point(r, translation)
        s = __scale_point(t, scale)
        translated_points.append(s)

    return translated_points

def __scale_point(p, scale):
    return Point(p.x*scale.x, p.y*scale.y, p.z*scale.z)

def __translate_point(p,t):
    return Point(p.x+t.x, p.y+t.y, p.z+t.z)

def __rotate_point(p, r):
    x_x = p.x
    y_x =  cos(r.x) * p.y - sin(r.x)*p.z
    z_x =  sin(r.x) * p.y + cos(r.x)*p.z

    x_y =  cos(r.y) * x_x + sin(r.y)*z_x
    y_y = y_x
    z_y = -sin(r.y) * x_x + cos(r.y)*z_x

    x =  cos(r.z) * x_y - sin(r.z)*y_y
    y =  sin(r.z) * x_y + cos(r.z)*y_y
    z = z_y

    return Point(x,y,z)


############################################################
#
############################################################
run_unittests = 0
run_from_file = 1
run_from_stdio = 2
generate_solids = 3
input_file = "test2.in"

action = run_from_stdio

def get_integer_from_data_source(data_source):
    input = data_source.readline()
    return int(input)

def get_numbers_from_data_source(data_source):
    input = data_source.readline()
    strings = input.split(" ")
    values = [float(s) for s in strings]
    return values

def get_rock_sharpness(data_source):
    number_of_points = get_integer_from_data_source(data_source)
    points = []
    for point in range(0, number_of_points):
        (x,y,z) = get_numbers_from_data_source(data_source)
        p = Point(x,y,z)
        points.append(p)
    convex_hull = ConvexHull(points)
    return convex_hull.minimum_average_angle


def main(data_source):
    number_of_rocks = get_integer_from_data_source(data_source)
    for rock in range(0, number_of_rocks):
        sharpness = get_rock_sharpness(data_source)
        print("{0:.4f}".format(sharpness))

if __name__ == "__main__":
    if action == run_unittests:
        suite = unittest.TestLoader().loadTestsFromTestCase(Math_tests)
        unittest.TextTestRunner(verbosity=2).run(suite)
        suite = unittest.TestLoader().loadTestsFromTestCase(Hull_tests)
        unittest.TextTestRunner(verbosity=2).run(suite)
    elif action == run_from_file:
        data_source = open(input_file)
        main(data_source)
    elif action == generate_solids:
        generate_random_solids()
    else:
        data_source = sys.stdin
        main(data_source)
