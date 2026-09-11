// In JavaScript, call(), apply(), and bind() are mainly used when you want to control what 'this' refers to inside a function.

function introduce(hobby01, hobby02, fun) {
    console.log("Hello, I'm", this.name, "from", this.address.city + ".", "I love", hobby01, "and", hobby02 + `. (${fun})`)
}

// function introduce(person) {
//     console.log("Hello, I'm ", person.name)
// }

// let introduce = () => {
//     console.log("Hello, I'm ", this.name)
// } // THIS WON'T WORK

let person = {
    name: "Arshdeep Singh",
    age: 26,
    address: {
        street: "21 Street",
        city: "Jagraon",
        district: "Ludhiana",
        state: "Punjab",
        country: "India",
        pin: 142026
    },
    full: function () {
        return this.name
    }
}

// introduce(person)


// call() → call the function now, arguments separately
introduce.call(person, "swimming", "running", "call()")

// apply() → call the function now, arguments as an array
introduce.apply(person, ["swimming", "running", "apply()"])

// bind() → don't call now; create a new function with fixed this
let introduceWithBind = introduce.bind(person, "swimming", "running", "bind()")
introduceWithBind()


// call inside another function
function setUsername(username) {
    this.username = username
}

function setCredentials(username, email, password) {

    setUsername.call(this, username)

    this.email = email
    this.password = password
}

// new tells JavaScript: “Create a new object and use this function/class to initialize it.”
const user = new setCredentials("arshdeepsingh", "arshdeepsingh@gmail.com", "Abc123++")

console.log(user)
