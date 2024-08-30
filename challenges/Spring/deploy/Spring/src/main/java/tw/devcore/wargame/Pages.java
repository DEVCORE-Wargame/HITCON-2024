package tw.devcore.wargame;

import javax.persistence.Entity;
import javax.persistence.Id;

@Entity
public class Pages {
    @Id
    public Long id;
    public String route;
    public String name;
}