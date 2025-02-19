import{j as e,W as p,Y as g,a as f}from"./app-BYFdZLTZ.js";import{T as i,I as n}from"./TextInput-BXplN5eu.js";import{I as l}from"./InputLabel-TFBVaYTv.js";import{P as h}from"./PrimaryButton-DDj-oKDi.js";import{G as j}from"./GuestLayout-BfEXR7Zs.js";import"./ApplicationLogo-C8E2hGGL.js";function b({className:a="",...r}){return e.jsx("input",{...r,type:"checkbox",className:"rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 "+a})}function F({status:a,canResetPassword:r}){const{data:t,setData:o,post:d,processing:c,errors:m,reset:u}=p({email:"",password:"",remember:!1}),x=s=>{s.preventDefault(),d(route("login"),{onFinish:()=>u("password")})};return e.jsxs(j,{children:[e.jsx(g,{title:"Log in"}),a&&e.jsx("div",{className:"mb-4 text-sm font-medium text-green-600",children:a}),e.jsxs("form",{onSubmit:x,children:[e.jsxs("div",{children:[e.jsx(l,{htmlFor:"email",value:"Email"}),e.jsx(i,{id:"email",type:"email",name:"email",value:t.email,className:"mt-1 block w-full",autoComplete:"username",isFocused:!0,onChange:s=>o("email",s.target.value)}),e.jsx(n,{message:m.email,className:"mt-2"})]}),e.jsxs("div",{className:"mt-4",children:[e.jsx(l,{htmlFor:"password",value:"Password"}),e.jsx(i,{id:"password",type:"password",name:"password",value:t.password,className:"mt-1 block w-full",autoComplete:"current-password",onChange:s=>o("password",s.target.value)}),e.jsx(n,{message:m.password,className:"mt-2"})]}),e.jsx("div",{className:"mt-4 block",children:e.jsxs("label",{className:"flex items-center",children:[e.jsx(b,{name:"remember",checked:t.remember,onChange:s=>o("remember",s.target.checked)}),e.jsx("span",{className:"ms-2 text-sm text-gray-600",children:"Remember me"})]})}),e.jsxs("div",{className:"mt-4 flex items-center justify-end",children:[r&&e.jsx(f,{href:route("password.request"),className:"rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2",children:"Forgot your password?"}),e.jsx(h,{className:"ms-4",disabled:c,children:"Log in"})]})]})]})}export{F as default};
