/*
 * File:   main.cpp
 * Author: AaronN
 *
 * Created on January 22, 2014, 3:04 PM
 */

#include <cstdlib>
#include <iostream>
#include <cmath>
#include <cstdio>
#include <string>

using namespace std;

const string N = "NEW";
const string C = "CHILD";
const string S = "SIBLING";
const string GP = "GRANDPARENT";
const string PA = "PARENT";
const string GC = "GRANDCHILD";

class R;

class P
{
public:
    static P * plist[1000];
    static int num;

    string name;
    R * parents;
    R * spouse;

    P(string name, R * parents)
    {
        this->name = name;
        this->parents = parents;

        plist[num] = this;
        num++;
    }

    static P * find(string name)
    {
        for (int i = 0; i < num; i++)
        {
            if (plist[i]->name == name)
                return plist[i];
        }
        return NULL;
    }

    P * getFather();
    P * getMother();
};

P * P::plist[1000];
int P::num = 0;

class R
{
public:
    static R * rlist[500];
    static int num;

    P * p1;
    P * p2;
    P * children[20];
    int numChildren;

    R(P * p1, P * p2)
    {
        this->p1 = p1;
        this->p2 = p2;
        p1->spouse = this;
        p2->spouse = this;
        this->numChildren = 0;

        rlist[num] = this;
        num++;
    }

    P * addChild(string name)
    {
        this->children[numChildren] = new P(name, this);
        P * child = this->children[numChildren];
        child->parents = this;
        numChildren++;
        return child;
    }

    void printChildren()
    {
        for (int i = 0; i < numChildren; i++)
        {
            if (i != 0) cout << " ";
            cout << children[i]->name;
        }
    }

    static R * find(string name1, string name2)
    {
        R * r;
        for (int i = 0; i < num; i++)
        {
            r = rlist[i];
            if ((r->p1->name == name1 && r->p2->name == name2)
                    || (r->p1->name == name2 && r->p2->name == name1))
            {
                return r;
            }
        }

        return NULL;
    }

};

R * R::rlist[500];
int R::num = 0;

inline P * P::getFather()
{
    if (parents == NULL) return NULL;
    return parents->p1;
};

inline P * P::getMother()
{
    if (parents == NULL) return NULL;
    return parents->p2;
};

/*
 *
 */
int main(int argc, char** argv)
{
    string in;


    while (1)
    {
        cin >> in;
        if (cin.eof()) break;

        if (in == N)
        {
            cin >> in;
            string child = "", p1 = "", p2 = "";
            cin >> child >> p1 >> p2;

            R * parents = R::find(p1, p2);
            if (parents == NULL)
            {
                P * pa1 = P::find(p1);
                P * pa2 = P::find(p2);

                if (pa1 == NULL)
                    pa1 = new P(p1, NULL);
                if (pa2 == NULL)
                    pa2 = new P(p2, NULL);

                parents = new R(pa1, pa2);
            }

            parents->addChild(child);
        }
        else if (in == C)
        {
            string parent = "";
            cin >> parent;

            R * s = P::find(parent)->spouse;

            if (s != NULL)
            {
                s->printChildren();
                cout << endl;
            }
        }
        else if (in == S)
        {
            string child = "";
            cin >> child;

            P * c = P::find(child);

            if (c->parents == NULL) continue;

            bool space = false;
            for (int i = 0; i < c->parents->numChildren; i++)
            {
                P * sibling = c->parents->children[i];
                if (sibling->name == child) continue;

                if (space) cout << " ";
                cout << sibling->name;
                space = true;
            }

            if (space)
                cout << endl;
        }
        else if (in == PA)
        {
            string child = "";
            cin >> child;

            P * c = P::find(child);

            if (c != NULL)
                cout << c->getFather()->name << " " << c->getMother()->name << endl;
        }
        else if (in == GP)
        {
            string child = "";
            cin >> child;

            P * c = P::find(child);
            P * father = c->getFather();
            P * mother = c->getMother();

            if (father->parents != NULL)
                cout << father->getFather()->name << " " << father->getMother()->name;
            if (father->parents != NULL && mother->parents != NULL)
                cout << " ";
            if (mother->parents != NULL)
                cout << mother->getFather()->name << " " << mother->getMother()->name;

            if (father->parents != NULL || mother->parents != NULL)
                cout << endl;
        }
        else if (in == GC)
        {
            string parent = "";
            cin >> parent;

            P * p = P::find(parent);

            if (p->spouse == NULL) continue;

            bool space = false;
            for (int i = 0; i < p->spouse->numChildren; i++)
            {
                R * s = p->spouse->children[i]->spouse;
                if (s != NULL)
                {
                    if (space) cout << " ";
                    s->printChildren();
                    space = true;
                }
            }

            if (space)
                cout << endl;
        }
        else
        {
            cout << "INVALID" << endl;
        }
    }

    return 0;
}

