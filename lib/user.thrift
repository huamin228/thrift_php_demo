namespace php user

struct UserProfile {
 1:i32 uid,
 2:string name,
}

service User {
   UserProfile info(1:i32 num1),
   i32  age()
}