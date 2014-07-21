
import java.util.ArrayList;
import java.util.Scanner;
import java.util.StringTokenizer;

public class ulticoder {

    private static void printList(ArrayList<String> l) {
        for (int i = 0; i < l.size() - 1; i++) {
            int pos = i;
            String min = l.get(i);
            
            for (int j = i + 1; j < l.size(); j++) {
                if(l.get(j).compareTo(min) < 0){
                    min = l.get(j);
                    pos = j;
                }
            }
            if(pos != i){
                l.set(pos, l.get(i));
                l.set(i, min);
            }
        }
        
        String st = "";
        
        for (String s : l) {
            if(st.equals("")){
                st += s;
            }
            else{
                st += " " + s;
            }
        }
        if(first){
            System.out.print(st); 
            first = false;
        } else{
            System.out.print('\n' + st);
            first = false;
        }
        
    }
    static boolean first = true;
    
    static class node{
        String name;
        ArrayList<node> sibling;
        ArrayList<node> parent;
        ArrayList<node> child;
        ArrayList<node> grandParent;
        ArrayList<node> grandChild;

        public node(String name) {
            this.name = name;
            this.sibling = new ArrayList<node>();
            this.parent = new ArrayList<node>();
            this.child = new ArrayList<node>();
            this.grandParent = new ArrayList<node>();
            this.grandChild = new ArrayList<node>();
        }
    }
    
    public static void main(String[] args) {
        Scanner sc = new Scanner(System.in);    
        ArrayList<node> f = new ArrayList<node>();
        
        while(sc.hasNext()){
            String line = sc.nextLine();
            while(line.equals("") && sc.hasNext()){
                line = sc.nextLine();
            }
            
            StringTokenizer st = new StringTokenizer(line);
            
            String operation = st.nextToken();
            
            String n;
            
            if(operation.equals("NEW")){
                st.nextToken();
                    n = st.nextToken();
                    String p1 = st.nextToken();
                    String p2 = st.nextToken();
                    
                    node pn1;
                    node pn2;
                    node nn;
                    
                    int i = fContains(f, n);
                    if(i < 0){
                        nn = new node(n);
                        f.add(nn);
                    } 
                    else{
                        nn = f.get(i);
                    }
                    
                    i = fContains(f, p1);
                    if(i < 0){
                        pn1 = new node(p1);
                        pn1.child.add(nn);
                        f.add(pn1);
                    }
                    else{
                        pn1 = f.get(i);
                        f.get(i).child.add(nn);
                    }
                    
                    i = fContains(f, p2);
                    if(i < 0){
                        pn2 = new node(p2);
                        f.add(pn2);
                    }
                    else{
                        pn2 = f.get(i);
                        f.get(i).child.add(nn);
                    }
                                        
                    i = fContains(f, n);
                    f.get(i).parent.add(pn1);
                    f.get(i).parent.add(pn2);
            }
            else if(operation.equals("SIBLING")){
                    n = st.nextToken();
                    node nn = f.get(fContains(f, n));
                    
                    node pn = nn.parent.get(0);
                    ArrayList<String> l = new ArrayList<String>();
                    
                    for (node c : pn.child) {
                        if(!c.name.equals(n)){
                            l.add(c.name);
                        }
                    }
                    
                    printList(l);
                
            }
            else if(operation.equals("PARENT")){    
                    n = st.nextToken();
                    node nn = f.get(fContains(f, n));
                    
                    ArrayList<String> l = new ArrayList<String>();
                    
                    for (node p : nn.parent) {
                        l.add(p.name);
                    }
                    
                    printList(l);
            }
            else if(operation.equals("CHILD")){
                    n = st.nextToken();
                    node nn = f.get(fContains(f, n));
                    
                    ArrayList<String> l = new ArrayList<String>();
                    
                    for (node c : nn.child) {
                        l.add(c.name);
                    }
                    
                    printList(l);
            }
            else if(operation.equals("GRANDPARENT")){
                    n = st.nextToken();
                    node nn = f.get(fContains(f, n));
                    
                    ArrayList<String> l = new ArrayList<String>();
                    
                    for (node p : nn.parent) {
                        for (node gp : p.parent) {    
                            l.add(gp.name);
                        }
                    }
                    
                    printList(l);
            }
            else if(operation.equals("GRANDCHILD")){
                    n = st.nextToken();
                    node nn = f.get(fContains(f, n));
                    
                    ArrayList<String> l = new ArrayList<String>();
                    
                    for (node c : nn.child) {
                        for (node gc : c.child) {             
                            l.add(gc.name);
                        }
                    }
                    
                    printList(l);
            }            
        }
    }
    
    public static int fContains (ArrayList<node> f, String n){
        for (int i = 0; i < f.size(); i++) {
            if(f.get(i).name.equals(n)){
                return i;
            }
        }
        return -1;
    }
}
